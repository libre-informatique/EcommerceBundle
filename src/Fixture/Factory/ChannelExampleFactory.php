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

namespace Librinfo\EcommerceBundle\Fixture\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\ChannelExampleFactory as BaseFactory;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ChannelExampleFactory extends BaseFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(array $options = [])
    {
        /**
         * @var ChannelInterface
         */
        $channel = parent::create($options);
        $channel->setAccountVerificationRequired($options['account_verified_required']);

        return $channel;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('account_verified_required', false)
            ->setAllowedTypes('account_verified_required', 'bool');
    }
}
