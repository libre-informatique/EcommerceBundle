<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;

class ShippingMethodAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_shipping_method';
    protected $baseRoutePattern = 'librinfo/ecommerce/shipping_method';
    protected $classnameLabel = 'ShippingMethod';
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'code',
    ];

    public function toString($object)
    {
        return $object->getCode() ?: $object->getId();
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        
        return $query;
    }
}
