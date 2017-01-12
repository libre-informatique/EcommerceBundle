<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\ProductBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Sylius\Component\Product\Model\ProductVariant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdminController extends CRUDController
{
    /**
     * This method is called from createAction.
     *
     * @param Request          $request
     * @param ProductVariant   $object
     *
     * @return Response|null
     */
    protected function preCreate(Request $request, $object)
    {
        if ($product_id = $request->get('product_id')) {
            $product = $this->get('sylius.repository.product')->find($product_id);
            if (!$product)
                throw $this->createNotFoundException(sprintf('unable to find Product with id : %s', $product_id));
            $object->setProduct($product);
        }
        return null;
    }
}