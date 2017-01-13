<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\ProductBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdmin extends CoreAdmin
{
    /**
     * @var ProductInterface
     */
    private $product;

    public function configureFormFields(FormMapper $mapper)
    {
        $product = $this->getProduct();
        $request = $this->getRequest();
        if ($request->getMethod() == 'GET' && !$request->get($this->getIdParameter()) && !$product) {
            // First step creation form with just the Product field
            $mapper
                ->with('form_tab_new_product_variant')
                    ->add('product', 'sonata_type_model_autocomplete',
                        ['property' => ['translations.name', 'code'],  'required' => true],
                        ['admin_code' => 'librinfo_product.admin.product'])
            ;
            return;
        }

        // Regular edit/create form
        parent::configureFormFields($mapper);

        // Limit the variant option values to the product options
        if ($product) {
            $repository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_option_value');
            $qb = $repository->createQueryBuilder('o')
                ->andWhere('o.option IN (SELECT o2 FROM LibrinfoProductBundle:Product p LEFT JOIN p.options o2 WHERE p = :product)')
                ->setParameter('product', $product)
            ;

            $mapper->add('optionValues', 'entity', [
                'query_builder' => $qb,
                'class' => 'Librinfo\\ProductBundle\\Entity\\ProductOptionValue',
                'multiple' => true,
                'required' => false,
                'choice_label' => 'fullName',
            ]);
        }
    }


    /**
     * @return ProductVariantInterface
     */
    public function getNewInstance()
    {
        $productVariantFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_variant');

        $object = $productVariantFactory->createNew();
        if ($this->getProduct()) {
            $object->setProduct($this->getProduct());
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        return $object;
    }


    /**
     * @return ProductInterface|null
     * @throws \Exception
     */
    public function getProduct()
    {
        if ($this->product)
            return $this->product;

        if ($this->subject && $product = $this->subject->getProduct()) {
            $this->product = $product;
            return $product;
        }

        if ($product_id = $this->getRequest()->get('product_id')) {
            $product = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product')->find($product_id);
            if (!$product)
                throw new \Exception(sprintf('Unable to find Product with id : %s', $product_id));
            $this->product = $product;
            return $product;
        }

        return null;
    }


}