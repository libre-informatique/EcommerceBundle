<?php

namespace Librinfo\ProductBundle\Entity;

use Librinfo\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use AppBundle\Entity\OuterExtension\LibrinfoProductBundle\ProductExtension;
use Sylius\Component\Core\Model\Product as BaseProduct;

class Product extends BaseProduct
{
    use OuterExtensible, ProductExtension;
}