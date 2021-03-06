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

/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ProductVariantExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;
use Doctrine\Common\Collections\ArrayCollection;

class ProductVariant extends BaseProductVariant
{
    use OuterExtensible,
    ProductVariantExtension;

    /**
     * Allow enabling of product variant.
     *
     * @var bool
     */
    protected $enabled = true;

    public function __construct()
    {
        parent::__construct();
        $this->initCollections();
        $this->initOuterExtendedClasses();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->getProduct()) {
            $string = $this->getProduct()->getName();
        } else {
            $string = '';
        }

        if (!$this->getOptionValues()->isEmpty()) {
            $string .= ' (';

            foreach ($this->getOptionValues() as $option) {
                $string .= $option->getOption()->getName() . ': ' . $option->getValue() . ', ';
            }

            $string = substr($string, 0, -2) . ')';
        } elseif ($this->getName()) {
            $string .= ' (' . $this->getName() . ')';
        } elseif ($this->getCode()) {
            $string .= ' (CODE: ' . $this->getCode() . ')';
        }

        return (string) $string;
    }

    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    public function getName(): string
    {
        // Dirty hack to handle sonata sub form management
        if ($this->currentLocale === null) {
            $this->setCurrentLocale('fr_FR');
        }

        return (string) $this->getTranslation()->getName();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
