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

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\CoreAdmin;

class SyliusGenericAdmin extends CoreAdmin
{
    protected $datagridValues = [
    '_page'       => 1,
    '_sort_order' => 'ASC',
    '_sort_by'    => 'code',
    ];

    /* @todo: remove this */
    /*
      public function configure()
      {
      parent::configure();
      
      dump($this->getConfigurationPool()->getContainer()->get('sylius.context.locale')->getLocaleCode());
      dump($this->getConfigurationPool()->getContainer()->get('sylius.locale_provider')->getDefaultLocaleCode());
      dump($this->getClass());
      dump(debug_backtrace(1));
      }
    */

    /* @todo: remove this */
    /*
      public function createQuery($context = 'list')
      {
      $query = parent::createQuery($context);
      
      return $query;
      }
    */
    
   
    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        //   dump(get_class_methods($object));
        if (method_exists(get_class($object), 'setCurrentLocale')) {
            $defaultLocale = $this->getConfigurationPool()->getContainer()->get('sylius.locale_provider')->getDefaultLocaleCode();
            $object->setCurrentLocale($defaultLocale);
            $object->setFallbackLocale($defaultLocale);
        }

        
        /* @todo :  Think about this cleaner way to Initialize locale and more (find a good way to find good the FactoryName) */
        /*
          $syliusFactory = $this->getConfigurationPool()->getContainer()->get($this->getFactoryName());
          $object = $syliusFactory->createNew();
        */
        
        // @todo : check if it useless or not
        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }
        

        return $object;
    }
    
    
    
    public function getFactoryName()
    {
        throw new \Exception(sprintf(
            '%s have to be impelmented in %s',
            __FUNCTION__ /*__METHOD__*/,
            get_class($this)  /* __CLASS__ */
        ));
        
        return null;
    }
}
