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
use Librinfo\EcommerceBundle\Form\Type\PriceCentsType;

class ShippingMethodAdmin extends SyliusGenericAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        
        /* @todo: we should never use explicit tab and group name in php code as it may be changed in blast.yml */
        $formMapper
            ->tab('form_tab_general')->with('form_group_parameters')
            /* @todo : add this also in show and list */
            ->add(
                'configuration',
                'sonata_type_immutable_array',
                array(
                      'label' => false,
                      'required' => false,
                      'keys' => array(
                          /* @todo: find a way to loop on channel */
                          array('default', 'sonata_type_immutable_array', array(
                              //'attr' => array('class' => 'inline-block'),
                              'keys' => array(
                                  array('amount', PriceCentsType::class, array())
                              )
                          )),
                          array('FR_WEB', 'sonata_type_immutable_array', array(
                              //'attr' => array('class' => 'inline-block'),
                              'keys' => array(
                                  array('amount', PriceCentsType::class, array())
                              )
                          ))
                          //
                      )
                  )
            )
            ->end()->end();
        ;
    }
}
