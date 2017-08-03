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
use Sylius\Component\Core\Model\OrderInterface;

class OrderAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_order';
    protected $baseRoutePattern = 'librinfo/ecommerce/order';
    protected $classnameLabel = 'Order';
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query
            ->addSelect('channel')
            ->leftJoin("$alias.channel", 'channel')
            ->addSelect('customer')
            ->leftJoin("$alias.customer", 'customer')
            ->andWhere("$alias.state != :state")
            ->setParameter('state', OrderInterface::STATE_CART)
        ;

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if (isset($list['create'])) {
            unset($list['create']);
        }

        return $list;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }
}
