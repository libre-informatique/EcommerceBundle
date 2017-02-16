<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class AjaxController extends Controller
{
    public function setCustomerDefaultAddressAction($customerId, $addressId)
    {
        $manager = $this->getDoctrine()->getManager();
        $customer = $manager->getRepository('LibrinfoCRMBundle:Organism')->find($customerId);
        $address = $manager->getRepository('LibrinfoCRMBundle:Address')->find($addressId);
        
        if($customer->hasAddress($address))
        {
            $customer->setDefaultAddress($address);
            $manager->persist($customer);
            $manager->flush();
        }
        
        return new RedirectResponse($this->get('librinfo_crm.admin.organism')->generateObjectUrl('edit', $customer));
    }

}