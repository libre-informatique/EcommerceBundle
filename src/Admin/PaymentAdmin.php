<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sylius\Component\Core\Model\PaymentInterface;

class PaymentAdmin extends CoreAdmin
{

    protected $baseRouteName = 'admin_librinfo_ecommerce_payment';
    protected $baseRoutePattern = 'librinfo/ecommerce/payment';
    protected $classnameLabel = 'Payment';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];

        $query
            ->where(
                $query->expr()->in(
                    "$alias.state", [PaymentInterface::STATE_COMPLETED, PaymentInterface::STATE_REFUNDED]
                )
            )
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
        return $object->getOrder()->getNumber() ?: $object->getId();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
        $collection->remove('duplicate');
    }

}
