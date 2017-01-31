<?php

namespace Librinfo\EcommerceBundle\Entity\OuterExtension;

use Doctrine\Common\Collections\ArrayCollection;

trait HasCustomerConstructor
{
   /**
     * Will be called by OuterExtensible::initOuterExtendedClasses();
     */
    protected function initCustomerConstructor()
    {
        $this->orders = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->oauthAccounts = new ArrayCollection();
        $this->createdAt = new \DateTime();

        // Set here to overwrite default value from trait
        $this->enabled = false;
    }
}
