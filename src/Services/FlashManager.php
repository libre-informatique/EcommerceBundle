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

namespace Librinfo\EcommerceBundle\Services;

use Sonata\CoreBundle\FlashMessage\FlashManager as BaseFlashManager;

class FlashManager extends BaseFlashManager
{
    /**
     * @inherit
     */
    protected function rename($type, $value, $domain)
    {
        $flashBag = $this->getSession()->getFlashBag();

        foreach ($flashBag->get($value) as $message) {
            if (!is_iterable($message)) {
                // Default Sonata flash message format
                $message = $this->getTranslator()->trans($message, array(), $domain);
            } else {
                if (is_array($message) && array_key_exists('message', $message)) {
                    // Sylius flash message format : [message => ..., parameters => [...]]
                    $message = $this->getTranslator()->trans($message['message'], $message['parameters'], 'flashes');
                } else {
                    // Flash format not managed
                }
            }

            $flashBag->add($type, $message);
        }
    }
}
