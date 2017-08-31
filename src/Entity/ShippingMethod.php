<?php

namespace Librinfo\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\ShippingMethod as BaseShippingMethod;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;

//use Blast\BaseEntitiesBundle\Entity\Traits\Stringable;
//use Blast\BaseEntitiesBundle\Entity\Traits\Guidable;
//use Blast\BaseEntitiesBundle\Entity\Traits\Nameable;

class ShippingMethod extends BaseShippingMethod
{
    use OuterExtensible;//, Nameable, Stringable; //, Guidable;

    public function __toString()
    {
        return (string) '';
    }
}
