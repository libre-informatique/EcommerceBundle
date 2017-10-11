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
use Librinfo\EcommerceBundle\SalesJournal\SalesJournalService;
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
     * @var SalesJournalService
     */
    private $salesJournalService;

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

        $invoice = $this->invoiceFactory->createForOrder($object, Invoice::TYPE_DEBIT);

        $this->em->persist($invoice);

        $this->salesJournalService->traceDebitInvoice($object, $invoice);

        $this->em->flush();
    }

    public function generateCreditInvoice(OrderInterface $object)
    {
        $invoice = $this->invoiceFactory->createForOrder($object, Invoice::TYPE_CREDIT);

        $this->em->persist($invoice);

        $this->salesJournalService->traceCreditInvoice($object, $invoice);

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

    /**
     * @param SalesJournalService $salesJournalService
     */
    public function setSalesJournalService(SalesJournalService $salesJournalService): void
    {
        $this->salesJournalService = $salesJournalService;
    }
}
