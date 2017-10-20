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

namespace Librinfo\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use Sylius\Component\Order\Model\OrderInterface;
use Blast\CoreBundle\CodeGenerator\CodeGeneratorInterface;

class OrderCustomerManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CodeGeneratorInterface
     */
    private $codeGenerator;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function associateUserAndAddress(OrderInterface $object)
    {
        $customer = $object->getCustomer();
        $shippingAddress = $object->getShippingAddress();
        $billingAddress = $object->getBillingAddress();

        $customer->setIsIndividual(true);
        $customer->setIsCustomer(true);

        $customer->setFirstname($billingAddress->getFirstName());
        $customer->setLastname($billingAddress->getLastName());

        $customer->addAddress($shippingAddress);
        $customer->addAddress($billingAddress);

        $customer->addOrder($object);

        if ($this->codeGenerator !== null) {
            $customer->setCustomerCode($this->codeGenerator->generate($customer));
        }

        // $this->em->flush($customer);
    }

    /**
     * setCodeGenerator.
     *
     * @param CodeGeneratorInterface $codeGenerator
     */
    public function setCodeGenerator(CodeGeneratorInterface $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }
}
