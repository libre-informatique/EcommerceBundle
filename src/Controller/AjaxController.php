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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class AjaxController extends Controller
{
    /**
     * Edit order field.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function orderInlineEditAction(Request $request)
    {
        $value = $request->get('value');
        $setter = 'set' . ucfirst($request->get('field'));
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('LibrinfoEcommerceBundle:Order');

        $order = $repo->find($request->get('id'));

        $order->$setter($value);

        $manager->persist($order);
        $manager->flush();

        return new JsonResponse($value);
    }

    /**
     * Increase order item quantity.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addToOrderAction(Request $request)
    {
        return new JsonResponse($this->container->get('librinfo_ecommerce.order.item_updater')->updateItemCount($request->get('order'), $request->get('item'), true));
    }

    /**
     * Decrease order item quantity.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeFromOrderAction(Request $request)
    {
        $updater = $this->container->get('librinfo_ecommerce.order.item_updater');

        $result = $updater->updateItemCount($request->get('order'), $request->get('item'), false);

        if ($result['lastItem'] != null) {
            $result['message'] = $this->container->get('translator')->trans('There must be at least one item left in the order');
        }

        return new JsonResponse($result);
    }

    public function addnewProductAction(Request $request)
    {
        $newProduct = $this->container
            ->get('librinfo_ecommerce.order.updater')
            ->addProduct($request->get('orderId'), $request->get('variantId')
        );

        if ($newProduct['item'] === null) {
            // $this->container->get('sonata.core.flashmessage.manager')->
            $this->container->get('session')->getFlashBag()->add('error', 'cannot_edit_order_because_of_state');
        }

        return new JsonResponse('ok');
    }
}
