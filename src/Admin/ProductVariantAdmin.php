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

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdmin extends CoreAdmin
{

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

        $this->buildDefaultPricings($object);

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        return $object;
    }

    public function buildDefaultPricings($object) {
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
