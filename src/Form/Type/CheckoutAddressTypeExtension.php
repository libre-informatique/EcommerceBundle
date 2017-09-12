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

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType as BaseCheckoutAddressType;
use Symfony\Component\Validator\Constraints\Valid;
use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerGuestType;

class CheckoutAddressTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($builder->has('customer')) {
            $builder
                ->remove('customer')
            ;
        }
        $builder
            ->add('customer', CustomerGuestType::class, ['constraints' => [new Valid()]]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return BaseCheckoutAddressType::class;
    }
}
