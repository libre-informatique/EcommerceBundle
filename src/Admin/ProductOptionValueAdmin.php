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
use Sonata\AdminBundle\Form\FormMapper;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
* @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
*/
class ProductOptionValueAdmin extends CoreAdmin
{
/**
* @param FormMapper $mapper
*
* @todo  handle multiple locales
*/
public function configureFormFields(FormMapper $mapper)
{
parent::configureFormFields($mapper);

// If form is embedded
if ($this->getParentFieldDescription()) {
$mapper->remove($this->getParentFieldDescription()->getAssociationMapping()['mappedBy']);
}

// This is a hack to prevent having the "No locale has been set and current locale is undefined" error
// during the creation of a new ProductOptionValue in the ProductOption form.
// TODO: build the ProductOption form differently to handle multiple locales...
$builder = $mapper->getFormBuilder();
$admin = $this;
$builder->addEventListener(
FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($admin) {
if (!$event->getData()) {
$entity = $admin->getNewInstance();  // This will set the locale
$event->setData($entity);
}
}
);
}

/**
* @return ProductOptionValueInterface
*/
public function getNewInstance()
{
$productOptionFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_option_value');

$object = $productOptionFactory->createNew();

foreach ($this->getExtensions() as $extension) {
$extension->alterNewInstance($this, $object);
}

return $object;
}
}
