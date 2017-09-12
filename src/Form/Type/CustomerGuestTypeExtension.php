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
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerGuestType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CustomerGuestTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // @TODO : Add constraint on email field (NotBlank and Email)
        // $builder
        //     ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
        //         $data = $event->getData();
        //         $form = $event->getForm();
        //         dump($data, $form->get('email')->getConfig()->setOption('constrains',[new NotNull(), new NotBlank(), new Email()]));die;
        //         if (!isset($data['email'])) {
        //             return;
        //         }
        //
        //         $customer = $this->customerRepository->findOneBy(['email' => $data['email']]);
        //
        //         // assign customer only if there is no corresponding user account
        //         if (null !== $customer && null === $customer->getUser()) {
        //             $form = $event->getForm();
        //             $form->setData($customer);
        //         }
        //     });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return CustomerGuestType::class;
    }
}
