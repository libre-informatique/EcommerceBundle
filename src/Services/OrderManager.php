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
use SM\Factory\Factory;
use Librinfo\EcommerceBundle\Factory\InvoiceFactoryInterface;
use Librinfo\EcommerceBundle\Entity\Invoice;
use Sylius\Bundle\OrderBundle\NumberAssigner\OrderNumberAssigner;

class OrderManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Factory
     */
    private $stateMachine;

    /**
     * @var InvoiceFactoryInterface
     */
    private $invoiceFactory;

    /**
     * @var OrderNumberAssigner
     */
    private $orderNumberAssigner;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validateOrder(OrderInterface $object)
    {
        $order = $object;

        if ($order->getNumber() === null) {
            $this->orderNumberAssigner->assignNumber($order);
            $this->em->flush($order);
        }

        $stateMachine = $this->stateMachine->get($order, 'sylius_order');

        $stateMachine->apply('fulfill');
    }

    public function generateCreditInvoice(OrderInterface $object)
    {
        $invoice = $this->invoiceFactory->createForOrder($object, Invoice::TYPE_CREDIT);

        $this->em->persist($invoice);
        $this->em->flush();
    }

    /**
     * @param Factory stateMachine
     *
     * @return self
     */
    public function setStateMachine(Factory $stateMachine)
    {
        $this->stateMachine = $stateMachine;

        return $this;
    }

    /**
     * @param InvoiceFactoryInterface invoiceFactory
     */
    public function setInvoiceFactory(InvoiceFactoryInterface $invoiceFactory): void
    {
        $this->invoiceFactory = $invoiceFactory;
    }

    /**
     * @param OrderNumberAssigner orderNumberAssigner
     */
    public function setOrderNumberAssigner(OrderNumberAssigner $orderNumberAssigner): void
    {
        $this->orderNumberAssigner = $orderNumberAssigner;
    }
}
