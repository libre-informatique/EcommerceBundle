<?php

namespace Librinfo\EcommerceBundle\Entity;

use Sylius\Component\Addressing\Model\Zone as BaseZone;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ZoneExtension;
use Sylius\Component\Addressing\Model\ZoneInterface;

class Zone extends BaseZone
{

    use OuterExtensible,
       ZoneExtension;
    

    public function initZone()
    {
        $this->initOuterExtendedClasses();
    }
    
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * __clone()
     */
    public function __clone()
    {
        $this->id = null;
    }

}
