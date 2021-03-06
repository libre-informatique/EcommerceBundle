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

namespace Librinfo\EcommerceBundle\Entity\OuterExtension;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Librinfo\EcommerceBundle\Entity\ProductVariant;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
trait HasProductVariants
{
    /**
     * @var Collection
     */
    private $productVariants;

    public function initProductVariants()
    {
        $this->productVariants = new ArrayCollection();
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return self
     */
    public function addProductVariant(ProductVariant $productVariant)
    {
        $this->productVariants->add($productVariant);

        $this->setOwningSideRelation($productVariant);

        return $this;
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return self
     */
    public function removeProductVariant(ProductVariant $productVariant)
    {
        $this->productVariants->removeElement($productVariant);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductVariants()
    {
        return $this->productVariants;
    }
}
