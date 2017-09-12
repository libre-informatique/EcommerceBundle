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

use Blast\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Blast\CoreBundle\Admin\CoreAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductAdmin extends SyliusGenericAdmin
{
    use HandlesRelationsAdmin {
        configureFormFields as configFormHandlesRelations;
        configureShowFields as configShowHandlesRelations;
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if ($action === 'list') {
            $list = array_merge(
                $list,
                [
                ['template' => 'LibrinfoEcommerceBundle:CRUD:list__action_shop_link.html.twig'],
                ]
            );
        } elseif ($action === 'edit') {
            $list = array_merge(
                $list,
                [
                ['template' => 'LibrinfoEcommerceBundle:CRUD:global__action_shop_link.html.twig'],
                ]
            );
        }

        return $list;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('generateProductSlug', 'generate_product_slug');
        $collection->add('setAsCoverImage', 'setAsCoverImage');
    }

    /**
     * Configure create/edit form fields.
     *
     * @param FormMapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        //calls to methods of traits
        $this->configFormHandlesRelations($mapper);
        $admin = $this;
        $mapper->getFormBuilder()->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($admin) {
                $form = $event->getForm();
                $formData = $event->getData();
                $subject = $admin->getSubject($formData);

                // Avoid variants submit (because it is already managed in ajax)
                if ($form->has('variants')) {
                    $form->remove('variants');
                }
            }
        );

        $subject = $this->getSubject();

        if ($subject) {
            if ($subject->getId() === null) {
                $tabs = $mapper->getadmin()->getFormTabs();
                unset($tabs['form_tab_variants']);
                unset($tabs['form_tab_images']);
                $mapper->getAdmin()->setFormTabs($tabs);
                $mapper->remove('variants');
                $mapper->remove('images');
            }
        }
    }

    /**
     * Configure Show view fields.
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
    // public function getNewInstance()
    // {
    //     /**
    //      * @var ProductFactoryInterface *
    //      */
    //     $productFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product');

    //     /**
    //      * @var ProductInterface
    //      */
    //     $object = $productFactory->createNew();

    //     foreach ($this->getExtensions() as $extension) {
    //         $extension->alterNewInstance($this, $object);
    //     }

    //     return $object;
    // }

    public function prePersist($product)
    {
        parent::prePersist($product);
        $this->handleProductTaxonCollection($product);

        $slugGenerator = $this->getConfigurationPool()->getContainer()->get('sylius.generator.slug');
        $product->setSlug($slugGenerator->generate($product->getName()));
    }

    public function preUpdate($product)
    {
        parent::preUpdate($product);
        $this->handleProductTaxonCollection($product);
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        if ($object) {
            $id = $object->getId();
            $code = $object->getCode();

            $qb = $this->getModelManager()->createQuery(get_class($object), 'p');

            $qb
                ->where('p.id <> :currentId')
                ->andWhere('p.code = :currentCode')
                ->setParameters(
                    [
                    'currentId'   => $id,
                    'currentCode' => $code,
                    ]
                );

            if (count($qb->getQuery()->getResult()) != 0) {
                $errorElement
                    ->with('code')
                    ->addViolation('lisem.product_code.not_unique')
                    ->end();
            }
        }
    }

    private function handleProductTaxonCollection($product)
    {
        $taxons = $product->getTaxons();

        $exisingProductTaxons = $product->getProductTaxons();

        $productTaxonClass = $this->getConfigurationPool()->getContainer()->getParameter('sylius.model.product_taxon.class');

        foreach ($exisingProductTaxons as $exisingPTaxon) {
            if (!$taxons->contains($exisingPTaxon->getTaxon())) {
                $exisingProductTaxons->removeElement($exisingPTaxon);
            } else {
                $taxons->removeElement($exisingPTaxon->getTaxon());
            }
        }

        foreach ($taxons as $taxon) {
            $pTaxon = new $productTaxonClass();
            $pTaxon->setProduct($product);
            $pTaxon->setTaxon($taxon);

            $product->addProductTaxon($pTaxon);
        }
    }
}
