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

use Doctrine\ORM\EntityManager;
use Librinfo\EcommerceBundle\Factory\InvoiceFactoryInterface;
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
     * @var InvoiceFactoryInterface
     */
    private $invoiceFactory;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param SenderInterface         $emailSender
     * @param InvoiceFactoryInterface $invoiceFactory
     */
    public function __construct(SenderInterface $emailSender, InvoiceFactoryInterface $invoiceFactory, EntityManager $em)
    {
        $this->emailSender = $emailSender;
        $this->invoiceFactory = $invoiceFactory;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function sendConfirmationEmail(OrderInterface $order)
    {
        $attachment = $this->generateInvoice($order);
        $this->emailSender->send(Emails::ORDER_CONFIRMATION, [$order->getCustomer()->getEmail()], ['order' => $order], [$attachment]);
        @unlink($attachment);
    }

    /**
     * @param OrderInterface $order
     *
     * @return string invoice temporary file path
     */
    private function generateInvoice($order)
    {
        // create and persist the invoice entity
        $invoice = $this->invoiceFactory->createForOrder($order);
        $this->em->persist($invoice);
        $this->em->flush();

        // write invoice contents (pdf) in a temporary file
        $temp_file = sys_get_temp_dir() . '/lisem_invoice_' . $invoice->getNumber() . '.pdf';
        if (file_exists($temp_file)) {
            unlink($temp_file);
        }
        file_put_contents($temp_file, $invoice->getFile());

        return $temp_file;
    }
}
