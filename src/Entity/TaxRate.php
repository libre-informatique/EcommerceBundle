<?php

namespace Librinfo\EcommerceBundle\Entity;

use Sylius\Component\Taxation\Model\TaxRate as BaseTaxRate;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\TaxRateExtension;

class TaxRate extends BaseTaxRate
{

    use OuterExtensible,
        TaxRateExtension;

    public function initTaxRate()
    {
        $this->initOuterExtendedClasses();
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
