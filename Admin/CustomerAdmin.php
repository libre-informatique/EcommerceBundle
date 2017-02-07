<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\Admin;

//use Librinfo\CRMBundle\Entity\Contact;
//use Librinfo\CRMBundle\Entity\ContactPhone;
//use Librinfo\CRMBundle\Entity\Position;
use Librinfo\CRMBundle\Admin\CustomerAdmin as BaseCustomerAdmin;

class CustomerAdmin extends BaseCustomerAdmin
{   
    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $object->updateName();
    }
    
    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $object->updateName();
    }
    
    
    /**
     * {@inheritdoc}
     */
//    public function postPersist($organism)
//    {
//        if ( $organism->isIndividual() )
//        {
//            // Create a new Contact & Position associated to the organism
//            $contact = new Contact;
//            $contact->setTitle("");
//            $contact->setFirstname($organism->getFirstName());
//            $contact->setName($organism->getLastName());
//            $contact->setEmail($organism->getEmail());
//            $contact->addAddress($organism->getDefaultAddress());
//            $contact->setZip($organism->getZip());
//            $contact->setCity($organism->getCity());
//            $contact->setCountry($organism->getCountry());
//            $this->getModelManager()->create($contact);
//
//            foreach($organism->getPhones() as $oPhone)
//            {
//                $cPhone = new ContactPhone;
//                $cPhone->setPhoneType($oPhone->getPhoneType());
//                $cPhone->setNumber($oPhone->getNumber());
//                $cPhone->setContact($contact);
//                $this->getModelManager()->create($cPhone);
//            }
//
//            $position = new Position;
//            $position->setOrganism($organism);
//            $position->setContact($contact);
//            $position->setEmail($organism->getEmail());
//            $this->getModelManager()->create($position);
//        }
//    }
}