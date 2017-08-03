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

namespace Librinfo\EcommerceBundle\Controller;

use Blast\CoreBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class InvoiceCRUDController extends CRUDController
{
    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preCreate(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preEdit(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param Request $request
     * @param mixed   $object
     *
     * @return Response|null
     */
    protected function preDelete(Request $request, $object)
    {
        throw new AccessDeniedException();
    }

    /**
     * @param mixed $object
     *
     * @return Response|null
     */
    protected function preDuplicate($object)
    {
        throw new AccessDeniedException();
    }

    /**
     * show file action.
     *
     * @return response
     */
    public function showFileAction()
    {
        $id = $this->getRequest()->get($this->admin->getIdParameter());
        $invoice = $this->admin->getObject($id);

        if (!$invoice) {
            throw $this->createNotFoundException(sprintf('unable to find the invoice with id : %s', $id));
        }

        return new Response(
            $invoice->getFile(),
            200,
            ['Content-Type' => 'application/pdf']
        );
    }
}
