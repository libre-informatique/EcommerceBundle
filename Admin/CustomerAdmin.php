<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Admin;

use Librinfo\CRMBundle\Admin\CustomerAdmin as BaseCustomerAdmin;

class CustomerAdmin extends BaseCustomerAdmin
{
    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $object->updateName();
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $object->updateName();
    }

}