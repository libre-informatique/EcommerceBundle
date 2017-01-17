<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\ProductBundle\Controller;

use Blast\CoreBundle\Controller\CRUDController;
use Sylius\Component\Product\Model\ProductVariant;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantCRUDController extends CRUDController
{
    public function createAction($object = null)
    {
        $request = $this->getRequest();

        if (null !== $request->get('btn_create_for_product')) {
            $form = $this->admin->getForm();
            $form->handleRequest($request);
            $product_id = $form->getData()->getProduct()->getId();
            $url =  $this->admin->generateUrl('create', ['product_id' => $product_id]);
            return new RedirectResponse($url);
        }

        return parent::createAction($object);
    }

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
//        if ($product_id = $request->get('product_id')) {
//            $product = $this->get('sylius.repository.product')->find($product_id);
//            if (!$product)
//                throw $this->createNotFoundException(sprintf('unable to find Product with id : %s', $product_id));
//            $object->setProduct($product);
//        }
        return null;
    }
}