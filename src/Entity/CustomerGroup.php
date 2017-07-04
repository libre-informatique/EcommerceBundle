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

namespace Librinfo\EcommerceBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\CustomerGroupExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Customer\Model\CustomerGroup as BaseCustomerGroup;
use Doctrine\Common\Collections\ArrayCollection;

class CustomerGroup extends BaseCustomerGroup
{
    use OuterExtensible,
        CustomerGroupExtension;

    protected $customers;

    public function initCustomerGroup()
    {
        $this->customers = new ArrayCollection();
        $this->initOuterExtendedClasses();
    }

    public function __toString()
    {
        return (string) sprintf('%s (%s)', $this->getName(), $this->getCode());
    }

    /**
     * __clone().
     */
    public function __clone()
    {
        $this->id = null;
    }
}
