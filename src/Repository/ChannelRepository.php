<?php

namespace Librinfo\EcommerceBundle\Repository;

use Sylius\Bundle\ChannelBundle\Doctrine\ORM\ChannelRepository as BaseChannelRepository;

class ChannelRepository extends BaseChannelRepository
{

    public function getAvailableAndActiveChannels()
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->where('c.enabled = :enabled')
        ;

        $qb->setParameter('enabled', true);

        return $qb->getQuery()->getResult();
    }

}
