<?php

namespace Librinfo\EcommerceBundle\Entity;

use Sylius\Component\Taxation\Model\TaxRate as BaseTaxRate;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\TaxRateExtension;
use Sylius\Component\Addressing\Model\ZoneInterface;

class TaxRate extends BaseTaxRate
{

    use OuterExtensible,
        TaxRateExtension;
    
    /**
     * @var ZoneInterface
     */
    private $zone;

    public function initTaxRate()
    {
        $this->initOuterExtendedClasses();
    }
    
    /**
     * @return ZoneInterface
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param ZoneInterface $zone
     */
    function setZone(ZoneInterface $zone)
    {
        $this->zone = $zone;
    }

    
    public function __toString()
    {
        return (string) sprintf("%s (%s)", $this->getName(), $this->getCode());
    }

    /**
     * __clone()
     */
    public function __clone()
    {
        $this->id = null;
    }

}
