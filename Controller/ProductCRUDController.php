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
use Sylius\Component\Product\Model\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductCRUDController extends CRUDController
{
    /**
     * Generate product variant, based on product options
     * @todo !!
     */
    public function generateVariantsAction(Request $request)
    {
    }
}