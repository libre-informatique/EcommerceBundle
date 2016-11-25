<?php

namespace Librinfo\ProductBundle\Entity;

use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\Product as BaseProduct;

class Product extends BaseProduct
{
    use OuterExtensible;
}