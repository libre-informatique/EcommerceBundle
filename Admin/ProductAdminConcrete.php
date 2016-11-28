<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\ProductBundle\Admin;

use Blast\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductAdminConcrete extends ProductAdmin
{
    use HandlesRelationsAdmin {
        configureFormFields as configFormHandlesRelations;
        configureShowFields as configShowHandlesRelations;
    }

    /**
     * Configure create/edit form fields
     *
     * @param FormMapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        //calls to methods of traits
        $this->configFormHandlesRelations($mapper);
    }


    /**
     * Configure Show view fields
     *
     * @param ShowMapper $mapper
     */
    protected function configureShowFields(ShowMapper $mapper)
    {
        // call to aliased trait method
        $this->configShowHandlesRelations($mapper);
    }

    /**
     * @return ProductInterface
     */
    public function getNewInstance()
    {
        /** @var ProductFactoryInterface $productFactory **/
        $productFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product');

        /** @var ProductInterface $product */
        $object = $productFactory->createNew();

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        return $object;
    }


    public function prePersist($product)
    {
        parent::prePersist($product);

        $slugGenerator = $this->getConfigurationPool()->getContainer()->get('sylius.generator.slug');
        $product->setSlug($slugGenerator->generate($product->getName()));
    }


}