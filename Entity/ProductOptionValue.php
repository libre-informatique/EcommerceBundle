<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\ProductBundle\Entity;

use Sylius\Component\Product\Model\ProductOptionValue as BaseProductOptionValue;

class ProductOptionValue extends BaseProductOptionValue
{
    /**
     * @return string    "Option name: OptionValue value"
     */
    public function getFullName()
    {
        return sprintf('%s: %s', $this->option->getName(), $this->getValue());
    }

    /**
     * @return string    "Option code: OptionValue code"
     */
    public function getFullCode()
    {
        return sprintf('%s: %s', $this->option->getCode(), $this->getCode());
    }
}