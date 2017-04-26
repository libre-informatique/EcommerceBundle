<?php

use Sylius\Bundle\CoreBundle\Fixture\AbstractResourceFixture;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class CustomerSuiteFixture extends AbstractResourceFixture
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'lisem_customer';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResourceNode(ArrayNodeDefinition $resourceNode)
    {
        $resourceNode
            ->children()
                ->integerNode('customer_number')->defaultValue(4)->end()
                ->integerNode('customer_group_number')->defaultValue(2)->end()
                ->scalarNode('email_domain')->defaultValue('libre-informatique.fr')->end()
            ->end()
        ;
    }

}
