<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\CustomerGroupExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Customer\Model\CustomerGroup as BaseCustomerGroup;

class CustomerGroup extends BaseCustomerGroup
{

    use OuterExtensible,
        CustomerGroupExtension;

    public function __toString()
    {
        return (string) sprintf("%s (%s)", $this->getName(), $this->getCode());
    }

}
