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

namespace Librinfo\EcommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\OrderExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\Order as BaseOrder;

class Order extends BaseOrder
{
    use OuterExtensible,
        OrderExtension;

    /**
     * @var Collection
     */
    private $invoices;

    public function __construct()
    {
        parent::__construct();
        $this->invoices = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }

    /**
     * Add invoice.
     *
     * @param Invoice $invoice
     *
     * @return Order
     */
    public function addInvoice(Invoice $invoice)
    {
        $this->invoices[] = $invoice;

        return $this;
    }

    /**
     * Remove invoice.
     *
     * @param Invoice $invoice
     */
    public function removeInvoice(Invoice $invoice)
    {
        $this->invoices->removeElement($invoice);
    }

    /**
     * Get invoices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices()
    {
        return $this->invoices;
    }
}
