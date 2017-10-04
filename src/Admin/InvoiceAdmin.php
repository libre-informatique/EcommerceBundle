<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
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

        /* see InvoiceCRUDController which trigger AccessDeniedException for some route */
        $collection->remove('create');
        $collection->remove('edit');
        $collection->remove('delete');
        $collection->remove('duplicate');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy('o.createdAt', 'DESC');

        return $query;
    }
}
