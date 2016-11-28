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
use Sylius\Component\Attribute\Factory\AttributeFactoryInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

class ProductAttributeAdmin extends CoreAdmin
{
    /**
     * @return ProductAttributeInterface
     */
    public function getNewInstance()
    {
        /** @var AttributeFactoryInterface $attributeFactory **/
        $attributeFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_attribute');

        /** @var ProductAttributeInterface $object */
        $object = $attributeFactory->createTyped('text');

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        return $object;
    }

    public function prePersistOrUpdate($object, $method)
    {
        parent::prePersistOrUpdate($object, $method);

        dump($object->getType());
    }
}
