<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

class ShippingMethodTranslationAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.shipping_method_translation';

    protected $baseRouteName = 'admin_ecommerce_shipping_method_translation';
    protected $baseRoutePattern = 'ecommerce/shipping_method_translation';
}
