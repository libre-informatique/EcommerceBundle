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

namespace Librinfo\EcommerceBundle\SalesJournal\Strategy;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Librinfo\EcommerceBundle\Entity\Payment;
use Librinfo\EcommerceBundle\Entity\SalesJournalItem;

class CustomerStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    private $default = 'Customer';

    /**
     * @param CustomerInterface $payment
     *
     * @return string
     */
    public function getLabel($customer): string
    {
        return (string) $customer;
    }

    /**
     * @param mixed $orderOrPayment
     */
    public function handleOperation(SalesJournalItem $salesJournalItem, $orderOrPayment): void
    {
        if ($orderOrPayment instanceof OrderInterface) {
            $salesJournalItem->addDebit($orderOrPayment->getTotal());
        } elseif ($orderOrPayment instanceof Payment) {
            $salesJournalItem->addCredit($orderOrPayment->getAmount());
        }
    }
}
