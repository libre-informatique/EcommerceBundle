<?php

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;
use Blast\CoreBundle\Admin\Traits\EmbeddedAdmin;

class ChannelPricingAdmin extends CoreAdmin
{
    
    use EmbeddedAdmin;

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection->remove('create');
    }

    /**
     * @return ProductVariantInterface
     */
    public function getNewInstance()
    {
        $channelPricingFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.channel_pricing');

        $object = $channelPricingFactory->createNew();
        if ($this->getProductVariant()) {
            $object->setProductVariant($this->getProductVariant());
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        return $object;
    }

    public function getProductVariant()
    {
        if ($this->productVariant)
            return $this->productVariant;

        if ($this->subject && $productVariant = $this->subject->getProduct()) {
            $this->productVariant = $productVariant;
            return $productVariant;
        }

        if ($productVariant_id = $this->getRequest()->get('productVariant_id')) {
            $productVariant = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_variant')->find($productVariant_id);
            if (!$productVariant)
                throw new \Exception(sprintf('Unable to find Product Variant with id : %s', $productVariant_id));
            $this->productVariant = $productVariant;
            return $productVariant;
        }

        return null;
    }

}
