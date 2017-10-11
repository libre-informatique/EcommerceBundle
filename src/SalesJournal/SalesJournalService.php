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

namespace Librinfo\EcommerceBundle\SalesJournal;

use Sylius\Component\Order\Model\OrderInterface;
use Librinfo\EcommerceBundle\Entity\Invoice;
use Librinfo\EcommerceBundle\SalesJournal\Handler\OrderHandler;
use Librinfo\EcommerceBundle\SalesJournal\Factory\SalesJournalItemFactory;

class SalesJournalService
{
    /**
     * @var SalesJournalItemFactory
     */
    private $salesJournalItemFactory;

    /**
     * @var OrderHandler
     */
    private $orderHandler;

    public function traceCreditInvoice(OrderInterface $order, Invoice $invoice)
    {
        $this->orderHandler->generateItemsFromOrder($order, $invoice, Invoice::TYPE_DEBIT);
    }

    public function traceDebitInvoice(OrderInterface $order, Invoice $invoice)
    {
        $this->orderHandler->generateItemsFromOrder($order, $invoice, Invoice::TYPE_CREDIT);
    }

    public function tracePayment(OrderInterface $order, Payment $payment)
    {
    }

    /**
     * @param SalesJournalItemFactory $salesJournalItemFactory
     */
    public function setSalesJournalItemFactory(SalesJournalItemFactory $salesJournalItemFactory): void
    {
        $this->salesJournalItemFactory = $salesJournalItemFactory;
    }

    /**
     * @param OrderHandler orderHandler
     */
    public function setOrderHandler(OrderHandler $orderHandler): void
    {
        $this->orderHandler = $orderHandler;
    }
}
