<?php

/*
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Librinfo\EcommerceBundle\EventListener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Librinfo\UserBundle\EventListener\TraceableListener as BaseTraceableListener;

class TraceableListener extends BaseTraceableListener
{
    /**
     * sets Traceable dateTime and user information when persisting entity
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$this->hasTrait($entity, 'Librinfo\UserBundle\Entity\Traits\Traceable'))
            return;

        $this->logger->debug("[TraceableListener] Entering TraceableListener for « prePersist » event");
        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : NULL;
        $now = new DateTime('NOW');

        if ($user instanceof UserInterface && !$user instanceof ShopUserInterface) {
            $entity->setCreatedBy($user);
            $entity->setUpdatedBy($user);
        }
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    /**
     * sets Traceable dateTime and user information when updating entity
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ( !$this->hasTrait($entity, 'Blast\BaseEntitiesBundle\Entity\Traits\Traceable') )
            return;

        $this->logger->debug("[TraceableListener] Entering TraceableListener for « preUpdate » event");

        $user = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : NULL;
        $now = new DateTime('NOW');
        
        if ( $user instanceof UserInterface && !$user instanceof ShopUserInterface )
            $entity->setUpdatedBy($user);
        
        $entity->setUpdatedAt($now);
    }
}
