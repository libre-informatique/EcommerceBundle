<?php
/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\CodeGenerator;

use Doctrine\ORM\EntityManager;
use Blast\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Blast\CoreBundle\Exception\InvalidEntityCodeException;
use Librinfo\EcommerceBundle\Entity\Product;

class ProductCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Librinfo\EcommerceBundle\Entity\Product';
    const ENTITY_FIELD = 'code';

    private static $length = 3;

    /**
     * @var EntityManager
     */
    private static $em;

    public static function setEntityManager(EntityManager $em)
    {
        self::$em = $em;
    }

    /**
     * @param  Product $product
     * @return string
     * @throws InvalidEntityCodeException
     */
    public static function generate($product)
    {
        // TODO: improve this (fixed length...) and handle code unicity
        return strtoupper($product->getName());
    }

    /**
     * @param  string   $code
     * @param  Product  $product
     * @return boolean
     * @todo   ...
     */
    public static function validate($code, $product = null)
    {
        return true;
    }

    /**
     * @return string
     * @todo   ...
     */
    public static function getHelp()
    {
        return "";
    }
}