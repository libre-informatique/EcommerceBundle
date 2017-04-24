<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Controller;

use Blast\CoreBundle\Controller\CRUDController;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class SalesJournalCRUDController extends CRUDController
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
        // Orders must not be deleted after the checkout is completed
        if ($object->getState() != OrderInterface::STATE_CART)
            throw new AccessDeniedException('An order cannot be deleted after the checkout is completed. You should cancel it instead.');
    }

    /**
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preDuplicate($object)
    {
        throw new AccessDeniedException();
    }



}