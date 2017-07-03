<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Entity;

use AppBundle\Entity\OuterExtension\LibrinfoEcommerceBundle\ProductImageExtension;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Sylius\Component\Core\Model\ProductImage as BaseProductImage;
use Librinfo\MediaBundle\Entity\File;

class ProductImage extends BaseProductImage
{

    use OuterExtensible,
        ProductImageExtension;
    
    /**
     * @var File
     */
    protected $realFile;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getRealFile()
    {
        return $this->realFile;
    }

    public function setRealFile(File $file)
    {
        $this->realFile = $file;
        return $this;
    }
    
    public function getFile()
    {
        
        return parent::getFile();
    }

    public function setFile(\SplFileInfo $file)
    {
        // Shouldn't be used
        parent::setFile($file);
        return $this;
    }
    
    public function setOwner($owner)
    {
        parent::setOwner($owner);
        return $this;
    }

    public function setPath($path)
    {
        parent::setPath($path);
        return $this;
    }

    public function setType($type)
    {
        parent::setType($type);
        return $this;
    }

    public function __toString()
    {
        return (string) $this->getPath();
    }

}