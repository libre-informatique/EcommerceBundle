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

use Sylius\Component\Core\Model\OrderInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Blast\CoreBundle\Admin\CoreAdmin;

class OrderAdmin extends CoreAdmin
{
    /* @todo : remove this useless protected attributes */

    protected $baseRouteName = 'admin_librinfo_ecommerce_order';
    protected $baseRoutePattern = 'librinfo/ecommerce/order';
    protected $classnameLabel = 'Order';
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'DESC',
        '_sort_by'    => 'createdAt',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'show', 'batch', 'create'));
        $collection->add('updateShipping', $this->getRouterIdParameter() . '/updateShipping');
        $collection->add('updatePayment', $this->getRouterIdParameter() . '/updatePayment');
        $collection->add('cancelOrder', $this->getRouterIdParameter() . '/cancelOrder');
        $collection->add('validateOrder', $this->getRouterIdParameter() . '/validateOrder');
    }

    public function configureBatchActions($actions)
    {
        $actions = parent::configureBatchActions($actions);

        $actions['cancel'] = [
            'ask_confirmation' => true,
            'label'            => 'librinfo.label.cancel_order',
        ];

        $actions['validate'] = [
            'ask_confirmation' => true,
            'label'            => 'librinfo.label.fulfill_order',
        ];

        return $actions;
    }

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
            ->setParameter('state', OrderInterface::STATE_CART);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if (isset($list['create'])) {
            // unset($list['create']);
        }

        return $list;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }
}
