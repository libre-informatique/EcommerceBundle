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

use Sylius\Component\Core\Model\AdjustmentInterface;
use Librinfo\EcommerceBundle\Entity\SalesJournalItem;
use Librinfo\EcommerceBundle\SalesJournal\Strategy\StrategyInterface;

class OrderAdjustmentStrategy implements StrategyInterface
{
    /**
     * Adjustments that decrease elements cost.
     *
     * @var array
     */
    private $rrrAdjustments = [
        AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT,
        AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT,
        AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT,
        AdjustmentInterface::ORDER_UNIT_PROMOTION_ADJUSTMENT,
    ];

    /**
     * Adjustments that increase elements cost.
     *
     * @var array
     */
    private $chargeAdjustments = [
        AdjustmentInterface::SHIPPING_ADJUSTMENT,
        AdjustmentInterface::TAX_ADJUSTMENT,
    ];

    /**
     * @var string
     */
    private $default = 'RRR'; // Rabais, Remises et Ristournes

    /**
     * @param AdjustmentInterface $adjustment
     *
     * @return string
     */
    public function getLabel($adjustment): string
    {
        $allAdjustments = array_merge($this->rrrAdjustments, $this->chargeAdjustments);
        $adjustmentIdentifier = in_array($adjustment->getType(), $allAdjustments) ? $adjustment->getLabel() : $this->default;

        return $adjustmentIdentifier;
    }

    /**
     * @param AdjustmentInterface $adjustment
     */
    public function handleOperation(SalesJournalItem $salesJournalItem, $adjustment): void
    {
        if (!$adjustment->isCharge()) {
            $salesJournalItem->addCredit($adjustment->getAmount());
        } elseif ($adjustment->isCredit()) {
            $salesJournalItem->addDebit($adjustment->getAmount());
        }
    }
}
