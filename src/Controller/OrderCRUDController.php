<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Controller;

use Blast\CoreBundle\Controller\CRUDController;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Order\OrderTransitions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class OrderCRUDController extends CRUDController
{
    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preCreate(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preEdit(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preDelete(Request $request, $object)
    {
        if ($object->getState() != PaymentInterface::STATE_NEW) {
            throw new AccessDeniedException('An order cannot be deleted after the checkout is completed. You should cancel it instead.');
        }
    }

    /**
     * @param mixed $object
     *
     * @return Response|null
     */
    protected function preDuplicate($object)
    {
        throw new AccessDeniedException();
    }

    public function batchActionCancel(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_CANCEL, $request->request->get('idx'), $selectedModelQuery);
    }

    public function batchActionValidate(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        return $this->validateOrCancelOrders(OrderTransitions::TRANSITION_FULFILL, $request->request->get('idx'), $selectedModelQuery);
    }

    protected function validateOrCancelOrders($action, $targets, $selectedModelQuery)
    {
        $modelManager = $this->admin->getModelManager();

        $target = $modelManager->find($this->admin->getClass(), implode(ModelManager::ID_SEPARATOR, $targets));

        if ($target === null) {
            $this->addFlash('sonata_flash_info', 'flash_batch_cancel_or_validate_no_target');

            return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        }

        $selectedModels = $selectedModelQuery->execute();

        try {
            $stateMachineFactory = $this->container->get('sm.factory');
            foreach ($selectedModels as $selectedModel) {
                $stateMachine = $stateMachineFactory->get($selectedModel, OrderTransitions::GRAPH);
                $stateMachine->apply($action);
            }
            $this->container->get('sylius.manager.order')->flush();
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', $e->getMessage());

            return new RedirectResponse(
                $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
            );
        }

        $this->addFlash('sonata_flash_success', 'flash_batch_cancel_or_validate_success');

        return new RedirectResponse(
            $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
        );
    }
}
