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

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;

class ChannelAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_channel';
    protected $baseRoutePattern = 'librinfo/ecommerce/channel';
    protected $classnameLabel = 'Channel';
    // protected $datagridValues = [
    //     '_page'       => 1,
    //     '_sort_order' => 'DESC',
    //     '_sort_by'    => 'createdAt',
    // ];

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];

        return $query;
    }

    public function toString($object)
    {
        return $object->getCode() ?: $object->getId();
    }
}
