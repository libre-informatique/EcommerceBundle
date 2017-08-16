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
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;

/**
 * Manage order item quantity.
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class OrderItemUpdater
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $orderItemQuantityModifier;
    /**
     * @var MoneyFormatterInterface
     */
    private $moneyFormatter;
    /**
     * @var string
     */
    private $orderItemClass;

    /**
     * @param EntityManager                      $em
     * @param OrderItemQuantityModifierInterface $quantityModifier
     * @param MoneyFormatterInterface            $moneyFormatter
     * @param string                             $orderItemClass
     */
    public function __construct(EntityManager $em, OrderItemQuantityModifierInterface $quantityModifier, MoneyFormatterInterface $moneyFormatter, $orderItemClass)
    {
        $this->em = $em;
        $this->orderItemQuantityModifier = $quantityModifier;
        $this->moneyFormatter = $moneyFormatter;
        $this->orderItemClass = $orderItemClass;
    }

    /**
     * @param string $orderId
     * @param string $itemId
     * @param bool   $isAddition
     *
     * @return array
     */
    public function updateItemCount($orderId, $itemId, $isAddition)
    {
        $remove = false;
        $lastItem = false;
        $orderRepo = $this->em->getRepository('LibrinfoEcommerceBundle:Order');
        $itemRepo = $this->em->getRepository($this->orderItemClass);

        $order = $orderRepo->find($orderId);
        $item = $itemRepo->find($itemId);

        if ($isAddition) {
            $quantity = $item->getQuantity() + 1;
        } else {
            $quantity = $item->getQuantity() - 1;
        }

        if ($quantity < 1) {
            if ($order->countItems() < 2) {
                $lastItem = true;
            } else {
                $order->removeItem($item);
                $remove = true;
            }
        } else {
            $this->orderItemQuantityModifier->modify($item, $quantity);
            $item->recalculateUnitsTotal();
        }

        $order->recalculateItemsTotal();

        $this->em->persist($order);
        $this->em->flush();

        return $this->formatArray($order, $item, $remove, $lastItem);
    }

    /**
     * @param string $order
     * @param string $item
     *
     * @return array
     */
    private function formatArray($order, $item, $remove = false, $lastItem = true)
    {
        return [
            'remove' => $remove,
            'lastItem' => $lastItem,
            'item' => [
                'quantity' => $item->getQuantity(),
                'total' => $this->moneyFormatter->format(
                    $item->getTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
                'subtotal' => $this->moneyFormatter->format(
                    $item->getSubTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
            ],
            'order' => [
                'total' => $this->moneyFormatter->format(
                    $order->getTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
                'items-total' => $this->moneyFormatter->format(
                    $order->getItemsTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
            ],
        ];
    }
}
