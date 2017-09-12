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

class OrderStateManager
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
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validateOrder(OrderInterface $object)
    {
        $order = $object;

        $stateMachine = $this->stateMachine->get($order, 'sylius_order');

        $stateMachine->apply('fulfill');
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
}
