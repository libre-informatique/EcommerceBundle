<?php

/*
 * Copyright 
 * 
 * (C) 2015-2016 Libre Informatique
 * (C) Romain Sanchez
 * 
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\CustomerExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\Customer as BaseCustomer;

class Customer extends BaseCustomer
{
    use OuterExtensible, CustomerExtension;
}