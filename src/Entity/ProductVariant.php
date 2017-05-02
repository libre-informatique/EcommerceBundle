<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ProductVariantExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;
use Doctrine\Common\Collections\ArrayCollection;

class ProductVariant extends BaseProductVariant
{

    use OuterExtensible,
        ProductVariantExtension;

    public function __construct()
    {
        parent::__construct();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = $this->getProduct()->getName();

        if (!$this->getOptionValues()->isEmpty()) {
            $string .= ' (';

            foreach ($this->getOptionValues() as $option) {
                $string .= $option->getOption()->getName() . ': ' . $option->getValue() . ', ';
            }

            $string = substr($string, 0, -2) . ')';
        } elseif ($this->getName())
            $string .= ' (' . $this->getName() . ')';
        elseif ($this->getCode())
            $string .= ' (CODE: ' . $this->getCode() . ')';

        return $string;
    }

    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    public function getName()
    {
        // Dirty hack to handle sonata sub form management
        if ($this->currentLocale === null) {
            $this->setCurrentLocale("fr_FR");
        }

        return $this->getTranslation()->getName();
    }

}