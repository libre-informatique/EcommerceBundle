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

use Sonata\AdminBundle\Form\FormMapper;

class ShippingMethodAdmin extends SyliusGenericAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper->add(
            'configuration',
            'sonata_type_immutable_array',
            array(
                'keys' => array(
                    array('FR_WEB', 'sonata_type_immutable_array', array(
                        'keys' => array(
                            array('amount', 'text', array())
                        )
                    ))
                    //
                )
            )
        );
    }
}
