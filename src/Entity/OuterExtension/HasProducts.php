<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity\OuterExtension;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Librinfo\EcommerceBundle\Entity\Product;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
trait HasProducts
{
    /**
     * @var Collection
     */
    private $products;

    public function initProducts()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @param Product $product
     * @return self
     */
    public function addProduct(Product $product)
    {
        $this->products->add($product);

        $this->setOwningSideRelation($product);

        return $this;
    }

    /**
     * @param Product $product
     * @return self
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}