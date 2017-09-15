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

use Blast\CoreBundle\Admin\CoreAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

class InvoiceAdmin extends CoreAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('showFile', $this->getRouterIdParameter() . '/show_file');
        $collection->add('generate', '{order_id}/generate');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy('o.createdAt', 'DESC');

        return $query;
    }
}
