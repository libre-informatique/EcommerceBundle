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
use Sonata\AdminBundle\Show\ShowMapper;
use Librinfo\EcommerceBundle\Form\Type\PriceCentsType;

class ShippingMethodAdmin extends SyliusGenericAdmin
{


    public function genChannelArray(string $sonataType = 'sonata_type_immutable_array')
    {
        $channelKeyTab = [];

        foreach ($this->getConfigurationPool()->getContainer()
         ->get('sylius.repository.channel')->findAll() as $channel) {
            $channelKeyTab []=
            [$channel->getCode(), $sonataType, [
                'keys' => [
                    ['amount', PriceCentsType::class, []],
                ],
            ]];
        }
        return $channelKeyTab;
    }

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
            ['label'    => false,
            'required' => false,
            'keys'     => $this->genChannelArray('sonata_type_immutable_array')
            ]
        )
        ->end()->end();
    }
    
    protected function configureShowFields(ShowMapper $showMapper)
    {
        parent::configureShowFields($showMapper);
        
        /* @todo: it look like it is more easy to show multi level array in form than in show ... great sonata */
        // $showMapper
        //  ->tab('show_tab_general')->with('show_group_parameters')
        //   ->add('configuration', 'array', [])
        //  ->end()->end();
    }
}
