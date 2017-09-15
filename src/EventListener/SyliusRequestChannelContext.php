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

use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Channel\Context\RequestBased\RequestResolverInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

class SyliusRequestChannelContext implements RequestResolverInterface
{
    /**
     * @var string
     */
    private $fallbackChannelCode;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function findChannel(Request $request): ChannelInterface
    {
        if ($request->cookies->get('_channel_code') === null && $request->query->get('_channel_code') === null) {
            return $this->channelRepository->findOneByCode($this->fallbackChannelCode);
        }
    }

    /**
     * @param string fallbackChannelCode
     */
    public function setFallbackChannelCode($fallbackChannelCode)
    {
        $this->fallbackChannelCode = $fallbackChannelCode;
    }

    /**
     * @param ChannelRepositoryInterface channelRepository
     *
     * @return self
     */
    public function setChannelRepository(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;

        return $this;
    }
}
