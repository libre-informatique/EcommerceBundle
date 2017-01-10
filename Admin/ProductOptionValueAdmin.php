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
use Sylius\Component\Product\Model\ProductOptionValueInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductOptionValueAdmin extends CoreAdmin
{
    /**
     * @return ProductOptionValueInterface
     */
    public function getNewInstance()
    {
        $productOptionFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_option_value');

        $object = $productOptionFactory->createNew();

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