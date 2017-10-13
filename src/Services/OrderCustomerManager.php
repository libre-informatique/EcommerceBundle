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
use Librinfo\CRMBundle\Entity\Organism;

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
        $shippingAddress = $object->getShippingAddress();
        $billingAddress = $object->getBillingAddress();
        $givenCustomer = $object->getCustomer();

        $foundCustomer = $this->em->getRepository(Organism::class)
                       ->findOneBy(array('email' => $givenCustomer->getEmail())); /* As email must be unique */

        $customer = null; /* We love null :) */
        if (isset($foundCustomer)) {
            $customer = $foundCustomer;

            /* @todo: should not ask user to set a firstname and lastname if the email already exist */
        } else {
            $customer = $givenCustomer;
            $customer->setIsIndividual(true);
            $customer->setIsCustomer(true);

            $customer->setFirstname($billingAddress->getFirstName());
            $customer->setLastname($billingAddress->getLastName());
        }

        if ($this->codeGenerator !== null && $customer->getCustomerCode() === null) {
            $customer->setCustomerCode($this->codeGenerator->generate($customer));
        }

        /* @todo: check if addAddress check if adress already exist */
        $customer->addAddress($shippingAddress);
        $customer->addAddress($billingAddress);

        $object->setCustomer($customer);
        $customer->addOrder($object);



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
