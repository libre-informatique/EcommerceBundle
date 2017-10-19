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

use Sylius\Bundle\OrderBundle\Controller\OrderItemController as BaseOrderItemController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SyliusOrderItemController extends BaseOrderItemController
{
    public function addAction(Request $request): Response
    {
        $this->handleBulkForm($request);

        return parent::addAction($request);
    }

    private function handleBulkForm(Request $request)
    {
        $this->get('librinfo_ecommerce.event_listener.sylius_order_item_controller')->setBulkInformations([
            'bulk-surface'            => $request->request->get('bulk-surface'),
            'bulk-surface-unit'       => $request->request->get('bulk-surface-unit'),
            'bulk-weight'             => $request->request->get('bulk-weight'),
            'bulk-weight-unit'        => $request->request->get('bulk-weight-unit'),
            'product-variety-density' => $request->request->get('product-variety-density'),
            'product-variety-tkw'     => $request->request->get('product-variety-tkw'),
        ]);
    }
}
