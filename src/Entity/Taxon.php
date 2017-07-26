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

use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\TaxonExtension;
use Doctrine\Common\Collections\ArrayCollection;

class Taxon extends BaseTaxon
{
    use OuterExtensible,
       TaxonExtension;

    private $images;

    public function initTaxon()
    {
        $this->images = new ArrayCollection();
        $this->initOuterExtendedClasses();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * __clone().
     */
    public function __clone()
    {
        $this->id = null;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed images
     *
     * @return self
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }
}
