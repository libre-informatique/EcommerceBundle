<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sylius\Component\Core\Model\OrderInterface;

class OrderAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_order';
    protected $baseRoutePattern = 'librinfo/ecommerce/order';
    protected $classnameLabel = 'Order';

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

        if (isset($list['create']))
            unset($list['create']);

        return $list;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }

}
