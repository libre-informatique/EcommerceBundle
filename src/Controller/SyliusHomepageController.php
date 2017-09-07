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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Channel\Context\ChannelNotFoundException;

class SyliusHomepageController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $response = new Response();
        $channelContext = $this->container->get('sylius.context.channel');
        try {
            $this->container->get('sylius.context.channel')->getChannel();
        } catch (ChannelNotFoundException $e) {
            $defaultChannelCode = $this->container->getParameter('sylius_fallback_channel_code');
            $response->headers->setCookie(new Cookie('_channel_code', $defaultChannelCode));
        }

        $response->setContent($this->renderView('@SyliusShop/Homepage/index.html.twig'));

        return $response;
    }
}
