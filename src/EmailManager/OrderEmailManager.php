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

namespace Librinfo\EcommerceBundle\EmailManager;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Bundle\ShopBundle\EmailManager\OrderEmailManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

/**
 * Based on Sylius OrderEmailManager. Adds the invoice as attachment file to the order confirmation email.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class OrderEmailManager implements OrderEmailManagerInterface
{
    /**
     * @var SenderInterface
     */
    private $emailSender;

    /**
     * @param SenderInterface $emailSender
     */
    public function __construct(SenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmail(OrderInterface $order)
    {
        //$attachments = "/tmp/test-invoice.pdf";
        $attachments = [];
        // TODO: $attachments = order invoice

        $this->emailSender->send(Emails::ORDER_CONFIRMATION, [$order->getCustomer()->getEmail()], ['order' => $order], $attachments);
    }
}
