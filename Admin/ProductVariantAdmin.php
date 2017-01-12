<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\ProductBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sylius\Component\Product\Model\ProductVariantInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdmin extends CoreAdmin
{
    /**
     * @return ProductVariantInterface
     */
    public function getNewInstance()
    {
        $productVariantFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_variant');

        $object = $productVariantFactory->createNew();

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        return $object;
    }


    public function prePersist($product)
    {
        parent::prePersist($product);
    }


}