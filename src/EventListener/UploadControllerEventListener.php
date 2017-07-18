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

    public function preGetEntity(GenericEvent $event)
    {
        $repo = $this->em->getRepository('LibrinfoEcommerceBundle:ProductImage');

        /* @var $productImage ProductImage */
        $productImage = $repo->find($event->getSubject()['context']['id']);

        if ($productImage) {
            $file = $productImage->getRealFile();
            $file->isCover = $productImage->getType() === ProductImage::TYPE_COVER;

            $event->setArgument('file', $file);
        }
    }

    public function postGetEntity(GenericEvent $event)
    {
    }
}
