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

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sylius\Component\Product\Model\ProductOptionInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductOptionAdmin extends CoreAdmin
{
    /**
     * @return ProductOptionInterface
     */
    public function getNewInstance()
    {
        $productOptionFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_option');

        $object = $productOptionFactory->createNew();

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }
}
