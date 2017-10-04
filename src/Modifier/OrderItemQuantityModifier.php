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

namespace Librinfo\EcommerceBundle\Modifier;

use Sylius\Component\Order\Modifier\OrderItemQuantityModifier as BaseOrderItemQuantityModifier;
use Sylius\Component\Order\Model\OrderItemInterface;

class OrderItemQuantityModifier extends BaseOrderItemQuantityModifier
{
    private function increaseUnitsNumber(OrderItemInterface $orderItem, int $increaseBy): void
    {
        if (!$orderItem->isBulk()) {
            for ($i = 0; $i < $increaseBy; ++$i) {
                $this->orderItemUnitFactory->createForItem($orderItem);
            }
        } else {
            $this->orderItemUnitFactory->createForItem($orderItem);
        }
    }
}
