<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sylius\Component\Core\Model\OrderInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class PaymentMethodAdmin extends CoreAdmin
{

    protected $baseRouteName = 'admin_librinfo_ecommerce_payment_method';
    protected $baseRoutePattern = 'librinfo/ecommerce/payment_method';
    protected $classnameLabel = 'PaymentMethod';

    public function createQuery($context = 'list') {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null) {
        $list = parent::configureActionButtons($action, $object);

        if (isset($list['create'])) {
            unset($list['create']);
        }

        return $list;
    }

    public function toString($object) {
        return $object->getcode() ?: $object->getId();
    }

    protected function configureRoutes(RouteCollection $collection) {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

}
