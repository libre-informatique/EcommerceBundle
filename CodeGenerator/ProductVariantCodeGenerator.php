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
use Librinfo\EcommerceBundle\Entity\ProductVariant;

class ProductVariantCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Librinfo\EcommerceBundle\Entity\ProductVariant';
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
     * @param  ProductVariant $productVariant
     * @return string
     * @throws InvalidEntityCodeException
     */
    public static function generate($productVariant)
    {
        if (!$product = $productVariant->getProduct())
            throw new InvalidEntityCodeException('librinfo.error.missing_product');
        if (!$productCode = $product->getCode())
            throw new InvalidEntityCodeException('librinfo.error.missing_product_code');

        // TODO: improve this (use productVariant name or optionValues...) and handle code unicity
        return sprintf('%s-%s', $productCode, strtoupper($productVariant->getName()));
    }

    /**
     * @param  string         $code
     * @param  ProductVariant $productVariant
     * @return boolean
     * @todo   ...
     */
    public static function validate($code, $productVariant = null)
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