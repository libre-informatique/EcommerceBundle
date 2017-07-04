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
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Form\FormMapper;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\Factory;
use Librinfo\EcommerceBundle\Repository\ChannelRepository;
use Librinfo\EcommerceBundle\Entity\ProductVariant;

//use Blast\CoreBundle\Admin\Traits\EmbeddedAdmin;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdmin extends CoreAdmin
{
    //use EmbeddedAdmin;

    /**
     * @var ProductInterface
     */
    private $product;

    /**
     * @var string
     */
    protected $productAdminCode = 'librinfo_ecommerce.admin.product';

    public function configureFormFields(FormMapper $mapper)
    {
        $product = $this->getProduct();
        $request = $this->getRequest();
        if ($request->getMethod() == 'GET' && !$request->get($this->getIdParameter()) && !$product) {
            // First step creation form with just the Product field
            $options = ['property' => ['code', 'translations.name'], 'required' => true];
            $productAdmin = $this->getConfigurationPool()->getInstance($this->productAdminCode);
            if (is_callable([$productAdmin, 'SonataTypeModelAutocompleteCallback'])) {
                $options['callback'] = function ($admin, $property, $value) {
                    $admin->SonataTypeModelAutocompleteCallback($admin, $property, $value);
                };
            }
            $mapper
                ->with('form_tab_new_product_variant')
                ->add('product', 'sonata_type_model_autocomplete', $options, ['admin_code' => $this->productAdminCode])
            ;

            return;
        }

        // Regular edit/create form
        parent::configureFormFields($mapper);

        // Limit the variant option values to the product options
        if ($product) {
            $mapper->add('optionValues', 'entity', [
                'query_builder' => $this->optionValuesQueryBuilder(),
                'class' => 'Librinfo\\EcommerceBundle\\Entity\\ProductOptionValue',
                'multiple' => true,
                'required' => false,
                'choice_label' => 'fullName',
                ], [
                'admin_code' => 'librinfo_ecommerce_option_value.admin.product',
                ]);

            if (!$this->isChild() && $mapper->has('product')) {
                $mapper->remove('product');
            }
        }
    }

    /**
     * @return ProductVariantInterface
     */
    public function getNewInstance()
    {
        $productVariantFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_variant');

        /* @var $object ProductVariant */
        $object = $productVariantFactory->createNew();
        if ($this->getProduct()) {
            $object->setProduct($this->getProduct());
        }

        /* @var $channelPricingFactory Factory */
        $channelPricingFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.channel_pricing');

        /* @var $channelRepository ChannelRepository */
        $channelRepository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.channel');

        foreach ($channelRepository->getAvailableAndActiveChannels() as $channel) {
            $channelPricing = $channelPricingFactory->createNew();
            $channelPricing->setChannelCode($channel->getCode());
            $object->addChannelPricing($channelPricing);
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }

    /**
     * @return ProductInterface|null
     *
     * @throws \Exception
     */
    public function getProduct()
    {
        if ($this->product) {
            return $this->product;
        }

        if ($this->subject && $product = $this->subject->getProduct()) {
            $this->product = $product;

            return $product;
        }

        if ($product_id = $this->getRequest()->get('product_id')) {
            $product = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product')->find($product_id);
            if (!$product) {
                throw new \Exception(sprintf('Unable to find Product with id : %s', $product_id));
            }
            $this->product = $product;

            return $product;
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    protected function optionValuesQueryBuilder()
    {
        $repository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_option_value');
        $queryBuilder = $repository->createQueryBuilder('o')
            ->andWhere('o.option IN (SELECT o2 FROM LibrinfoEcommerceBundle:Product p LEFT JOIN p.options o2 WHERE p = :product)')
            ->setParameter('product', $this->product)
        ;

        return $queryBuilder;
    }
}
