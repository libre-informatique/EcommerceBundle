<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
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
            ->leftJoin("$alias.payments",'payments')
            ->andWhere("$alias.state = :state")
            ->orWhere("payments.state = :statePayments")
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

        if (isset($list['create']))
            unset($list['create']);

        return $list;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }

}
