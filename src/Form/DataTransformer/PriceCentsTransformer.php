<?php

namespace Librinfo\EcommerceBundle\Form\DataTransformer;

class PriceCentsTransformer implements \Symfony\Component\Form\DataTransformerInterface
{

    /**
     * reverseTransform : from currency format to cents
     * 
     * @param type $value
     * @return type
     */
    public function reverseTransform($value) {
        return (int) ($value * 100);
    }

    /**
     * transform : from cents to currency format
     * 
     * @param type $value
     * @return type
     */
    public function transform($value) {
        return number_format((float) $value/100, 2, '.', '');
    }

}
