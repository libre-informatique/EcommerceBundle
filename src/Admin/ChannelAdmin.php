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

use Blast\CoreBundle\Admin\CoreAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChannelAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_librinfo_ecommerce_channel';
    protected $baseRoutePattern = 'librinfo/ecommerce/channel';
    protected $classnameLabel = 'Channel';
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'ASC',
        '_sort_by'    => 'code',
    ];

    public function toString($object)
    {
        return $object->getCode() ?: $object->getId();
    }

    /**
     * @param FormMapper $mapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        $syliusThemeConfig = $this->getConfigurationPool()->getContainer()->get('sylius.theme.configuration.provider')->getConfigurations();
        $listOfThemes = [
            'default' => 'Default Sylius theme',
        ];
        foreach ($syliusThemeConfig as $k => $conf) {
            $listOfThemes[$conf['name']] = $conf['title'];
        }

        $groups = $this->getFormGroups();

        $mapper->remove('taxCalculationStrategy');
        $mapper->add('taxCalculationStrategy', ChoiceType::class, [
            'label'    => 'librinfo.label.taxCalculationStrategy',
            'choices'  => array_flip($this->getConfigurationPool()->getContainer()->getParameter('sylius.taxation.calculation_strategy.list_values')),
            'required' => true,
            'attr'     => [
                'class'=> 'inline-block',
                'width'=> 50,
            ],
        ]);

        $mapper->remove('themeName');
        $mapper->add('themeName', ChoiceType::class, [
            'label'    => 'librinfo.label.themeName',
            'choices'  => array_flip($listOfThemes),
            'required' => true,
            'attr'     => [
                'class'=> 'inline-block',
                'width'=> 50,
            ],
        ]);

        $tabs = $this->getFormTabs();
        unset($tabs['default']);
        $this->setFormTabs($tabs);

        $this->setFormGroups($groups);
    }
}
