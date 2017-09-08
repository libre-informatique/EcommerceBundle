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

namespace Librinfo\EcommerceBundle\Admin;

class ShippingMethodAdmin extends SyliusGenericAdmin
{
    /*    protected $datagridValues = [
          '_page'       => 1,
          '_sort_order' => 'ASC',
          '_sort_by'    => 'code',
          ];

          public function createQuery($context = 'list')
          {
          $query = parent::createQuery($context);

          return $query;
          }

          public function getNewInstance()
          {


          $syliusFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.shipping_method');
          $object = $syliusFactory->createNew();


          return $object;
          }
    */
}
