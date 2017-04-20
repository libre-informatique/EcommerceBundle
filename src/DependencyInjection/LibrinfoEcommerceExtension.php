<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\DependencyInjection;

use Blast\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LibrinfoEcommerceExtension extends BlastCoreExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadCodeGenerators(ContainerBuilder $container, array $config)
    {
        foreach(['product', 'product_variant'] as $cg)
            $container->setParameter("librinfo_ecommerce.code_generator.$cg", $config['code_generator'][$cg]);
        return $this;
    }
}
