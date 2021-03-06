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

namespace Librinfo\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\Channel as BaseChannel;
use Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible;
use Blast\BaseEntitiesBundle\Entity\Traits\Stringable;
/* @todo reference to AppBundle should be removed */
use AppBundle\Entity\OuterExtension\ChannelExtension;

/**
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class Channel extends BaseChannel
{
    use OuterExtensible, Stringable, ChannelExtension;
}
