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

namespace Librinfo\EcommerceBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Sylius\Component\Core\Model\ProductTaxon;
use Doctrine\Common\Collections\ArrayCollection;

class ProductTaxonTransformer implements DataTransformerInterface
{
    /**
     * reverseTransform : from currency format to cents.
     *
     * @param type $value
     *
     * @return type
     */
    public function reverseTransform($value)
    {
        if (!$value instanceof \Traversable) {
            $value = [];
        }
        $productTaxonList = new ArrayCollection();
        foreach($value as $taxon) {
            $productTaxonList->add($taxon);
        }

        return $productTaxonList;
    }

    /**
     * transform : from cents to currency format.
     *
     * @param type $value
     *
     * @return type
     */
    public function transform($value)
    {
        if($value === null) {
            $value = new ArrayCollection();
        }

        return $value->toArray();
    }
}
