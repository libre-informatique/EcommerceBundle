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

namespace Librinfo\EcommerceBundle\SalesJournal\Handler;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Librinfo\EcommerceBundle\Entity\Invoice;
use Librinfo\EcommerceBundle\SalesJournal\Guesser\GuesserInterface;
use Librinfo\EcommerceBundle\SalesJournal\Factory\SalesJournalItemFactory;
use Librinfo\EcommerceBundle\Repository\SalesJournalItemRepository;

class OrderHandler
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var GuesserInterface
     */
    private $orderItemGuesser;

    /**
     * @var SalesJournalItemFactory
     */
    private $salesJournalItemFactory;

    /**
     * @var SalesJournalItemRepository
     */
    private $salesJournalItemRepository;

    public function generateItemsFromOrder(OrderInterface $order, Invoice $invoice, $operationType)
    {
        if ($operationType !== Invoice::TYPE_DEBIT && $operationType !== Invoice::TYPE_CREDIT) {
            throw new \Exception('Operation type must be Invoice::TYPE_DEBIT or Invoice::TYPE_CREDIT');
        }

        $this->items = [];

        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $type = $this->handleItemType($orderItem);

            if (array_key_exists($type, $this->items)) {
                $salesJournalItem = $this->items[$type];
            } else {
                $salesJournalItem = $this->salesJournalItemFactory->newSalesJournalItem($type, $order, $invoice);
                $this->items[$type] = $salesJournalItem;
            }

            $salesJournalItem->addDebit($orderItem->getTotal());
        }

        foreach ($this->items as $item) {
            $this->salesJournalItemRepository->add($item);
        }
    }

    private function handleItemType(OrderItemInterface $orderItem)
    {
        return $this->orderItemGuesser->guessType($orderItem);
    }

    /**
     * @param GuesserInterface orderItemGuesser
     */
    public function setOrderItemGuesser(GuesserInterface $orderItemGuesser): void
    {
        $this->orderItemGuesser = $orderItemGuesser;
    }

    /**
     * @param SalesJournalItemFactory salesJournalItemFactory
     */
    public function setSalesJournalItemFactory(SalesJournalItemFactory $salesJournalItemFactory): void
    {
        $this->salesJournalItemFactory = $salesJournalItemFactory;
    }

    /**
     * @param SalesJournalItemRepository $salesJournalItemRepository
     */
    public function setSalesJournalItemRepository(SalesJournalItemRepository $salesJournalItemRepository): void
    {
        $this->salesJournalItemRepository = $salesJournalItemRepository;
    }
}
