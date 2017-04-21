<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sylius\Component\Core\Model\OrderInterface;

class PaymentAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_payment';
    protected $baseRoutePattern = 'librinfo/ecommerce/payment';
    protected $classnameLabel = 'Payment';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
//        $query
//            ->leftJoin("$alias.order", 'order')
//            ->addSelect('order.channel')
//            ->leftJoin("order.channel", 'channel')
//            ->addSelect('order.customer')
//            ->leftJoin("order.customer", 'customer')
//            ->andWhere("$alias.state != :state")
//            ->setParameter('state', OrderInterface::STATE_CART)
//        ;
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
        return $object->getOrder()->getNumber() ?: $object->getId();
    }

}
