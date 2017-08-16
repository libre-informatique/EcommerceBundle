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
use Sylius\Component\Core\Model\PaymentInterface;

class SalesJournalAdmin extends OrderAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_sales_journal';
    protected $baseRoutePattern = 'librinfo/ecommerce/sales_journal';
    protected $classnameLabel = 'SalesJournal';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query
            ->leftJoin("$alias.payments", 'payments')
            ->andWhere("$alias.state = :state")
            ->orWhere('payments.state = :statePayments')
            ->setParameter('state', OrderInterface::STATE_FULFILLED)
            ->setParameter('statePayments', PaymentInterface::STATE_COMPLETED)
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

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }
}
