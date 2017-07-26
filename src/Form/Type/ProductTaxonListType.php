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

namespace Librinfo\EcommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Librinfo\EcommerceBundle\Form\DataTransformer\ProductTaxonTransformer;

class ProductTaxonListType extends AbstractType
{
    private $em;

    private $taxonClass;
    private $productTaxonClass;

    public function __construct(EntityManagerInterface $em, $productTaxonClass, $taxonClass)
    {
        // parent::__construct($propertyAccessor);
        $this->em = $em;
        $this->productTaxonClass = $productTaxonClass;
        $this->taxonClass = $taxonClass;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // parent::configureOptions($resolver);
        $repo = $this->em->getRepository($this->taxonClass);
        $qb = null;
        if (method_exists($repo, 'createListQueryBuilder')) {
            $qb = $repo->createListQueryBuilder('o');
        } else {
            $qb = $repo->createQueryBuilder('o');
        }

        $qb->orderBy('o.root', 'ASC');
        $qb->orderBy('o.left', 'ASC');

        $taxons = $qb->getQuery()->getResult();

        array_walk($taxons, function (&$item) {
            $item->displayName = str_repeat('- - ', $item->getLevel()) . $item->getName();
        });

        $resolver->setDefaults([
            'choices' => $taxons,
            'class' => $this->productTaxonClass
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'librinfo_type_product_taxon_list';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ProductTaxonTransformer();
        $builder->addModelTransformer($transformer);
    }
}
