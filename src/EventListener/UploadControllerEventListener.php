<?php

namespace Librinfo\EcommerceBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\ORM\EntityManager;
use Librinfo\MediaBundle\Events\UploadControllerEventListener as BaseUploadControllerEventListener;
use Librinfo\EcommerceBundle\Entity\ProductImage;

class UploadControllerEventListener extends BaseUploadControllerEventListener
{
    
    /**
     * @var EntityManager
     */
    protected $em;
    
    public function preGetEntity(GenericEvent $event) {
        
        $repo = $this->em->getRepository('LibrinfoEcommerceBundle:ProductImage');
        
        /* @var $productImage ProductImage */
        $productImage = $repo->find($event->getSubject()['context']['id']);
        
        $file = $productImage->getRealFile();
        
        $event->setArgument('file', $file);
    }
    
    public function postGetEntity(GenericEvent $event) {
        
    }

}
