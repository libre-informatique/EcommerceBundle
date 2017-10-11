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

namespace Librinfo\EcommerceBundle\SalesJournal\Guesser\OrderItem;

use Sylius\Component\Core\Model\OrderItemInterface;
use Librinfo\EcommerceBundle\Entity\ProductVariant;
use Librinfo\EcommerceBundle\SalesJournal\Guesser\GuesserInterface;

class TaxCategoryBasedOrderItemGuesser implements GuesserInterface
{
    /**
     * @var string
     */
    private $default = 'No VAT';

    public function guessType(OrderItemInterface $item)
    {
        /** @var ProductVariant $variant */
        $variant = $item->getVariant();
        $itemIdentifier = $variant->getTaxCategory() ? $variant->getTaxCategory()->getName() : $this->default;

        return $itemIdentifier;
    }
}
