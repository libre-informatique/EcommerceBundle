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
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ProductExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\ImageInterface;

class Product extends BaseProduct
{
    use OuterExtensible,
    ProductExtension;

    /**
     * @var Collection|ImageInterface[]
     */
    protected $images;

    protected $taxons = null;

    public function __construct()
    {
        parent::__construct();
        $this->initOuterExtendedClasses();
        $this->images = new ArrayCollection();
        $this->productTaxons = new ArrayCollection();
        $this->taxons = new ArrayCollection();
    }

    public function getImages()
    {
        return $this->images;
    }

    public function setImages(ArrayCollection $images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * alias for LibrinfoMediaBundle/CRUDController::handleFiles().
     *
     * @param File $file
     *
     * @return Variety
     */
    public function addLibrinfoFile(ProductImage $file = null)
    {
        if (!$this->images->contains($file)) {
            $this->images->add($file);
        }

        return $this;
    }

    /**
     * alias for LibrinfoMediaBundle/CRUDController::handleFiles().
     *
     * @param File $file
     *
     * @return Variety
     */
    public function removeLibrinfoFile(ProductImage $file)
    {
        if ($this->images->contains($file)) {
            $this->images->removeElement($file);
        }

        return $this;
    }

    public function getTaxons()
    {
        // $this->initTaxons();

        if ($this->taxons === null) {
            return $this->getTaxonsFromProductTaxons();
        }

        return $this->taxons;
    }

    public function setTaxons($taxons)
    {
        $this->initTaxons();

        foreach ($taxons as $taxon) {
            $this->taxons->add($taxon);
        }

        return $this;
    }

    public function __toString()
    {
        return (string) parent::__toString();
    }

    private function initTaxons()
    {
        if ($this->taxons === null) {
            $this->taxons = new ArrayCollection();
        }
    }

    public function getTaxonsFromProductTaxons()
    {
        $this->initTaxons();

        $taxons = new ArrayCollection();

        $pTaxons = $this->getProductTaxons();
        foreach ($pTaxons as $pt) {
            $taxons->add($pt->getTaxon());
        }

        return $taxons;
    }
}
