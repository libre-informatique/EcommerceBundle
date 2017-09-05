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

use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Blast\BaseEntitiesBundle\Entity\Traits\Stringable;
/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\TaxonExtension;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\ImageInterface;

class Taxon extends BaseTaxon implements TaxonInterface
{
    // use OuterExtensible, Stringable, TaxonExtension;
    use Stringable;

    private $images;

    public function initTaxon()
    {
        $this->images = new ArrayCollection();
        //    $this->initializeTranslationsCollection();
        $this->initOuterExtendedClasses();
    }

    /*
    public function __toString()
    {
    return (string) $this->getName();
    }*/

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

    /**
     * @param string $type
     *
     * @return Collection|ImageInterface[]
     */
    public function getImagesByType($type)
    {
        return new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function hasImages()
    {
        return false;
    }

    /**
     * @param ImageInterface $image
     *
     * @return bool
     */
    public function hasImage(ImageInterface $image)
    {
        return false;
    }

    /**
     * @param ImageInterface $image
     */
    public function addImage(ImageInterface $image)
    {
    }

    /**
     * @param ImageInterface $image
     */
    public function removeImage(ImageInterface $image)
    {
    }

    public function getName()
    {
        // Dirty hack to handle sonata sub form management
        if ($this->currentLocale === null) {
            $this->setCurrentLocale('fr_FR');
        }

        return $this->getTranslation()->getName();
    }
}
