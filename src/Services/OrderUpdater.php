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
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;
use SM\Factory\Factory;

/**
 * Add products to existing order.
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class OrderUpdater
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var FactoryInterface
     */
    private $orderItemFactory;

    /**
     * @var OrderItemUnitFactoryInterface
     */
    private $orderItemUnitFactory;

    /**
     * @var Factory
     */
    private $smFactory;

    /**
     * @param EntityManager                 $em
     * @param ChannelContextInterface       $channelContext
     * @param FactoryInterface              $orderItemFactory
     * @param OrderItemUnitFactoryInterface $orderItemUnitFactory
     * @param Factory                       $smFactory
     */
    public function __construct(
        EntityManager $em,
        ChannelContextInterface $channelContext,
        FactoryInterface $orderItemFactory,
        OrderItemUnitFactoryInterface $orderItemUnitFactory,
        Factory $smFactory
    ) {
        $this->em = $em;
        $this->channel = $channelContext->getChannel();
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemUnitFactory = $orderItemUnitFactory;
        $this->smFactory = $smFactory;
    }

    /**
     * @param string $orderId
     * @param string $variantId
     */
    public function addProduct($orderId, $variantId)
    {
        //Retrieve order
        $order = $this->em
            ->getRepository('LibrinfoEcommerceBundle:Order')
            ->find($orderId);

        $orderStateMachine = $this->smFactory->get($order, 'sylius_order');

        if ($orderStateMachine->getState() === 'cancelled' || $orderStateMachine->getState() === 'fulfilled') {
            $item = null;
        } else {
            //Retrieve product variant
            $variant = $this->em
                ->getRepository('LibrinfoEcommerceBundle:ProductVariant')
                ->find($variantId);

            //Create new OrderItem
            $item = $this->orderItemFactory->createNew();
            $item->setVariant($variant);
            $item->setOrder($order);
            $item->setUnitPrice(
                $variant->getchannelPricingForChannel($this->channel)->getPrice()
            );

            //Create OrderItemUnit from OrderItem
            $this->orderItemUnitFactory->createForItem($item);

            //Recalculate Order totals
            $item->recalculateUnitsTotal();
            $order->recalculateItemstotal();

            //Persist Order
            $this->em->persist($order);
            $this->em->flush();
        }

        return [
        'item'   => $item,
        'object' => $order,
            ];
    }
}
