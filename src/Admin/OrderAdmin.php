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
use Librinfo\EcommerceBundle\Form\Type\OrderAddressType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class OrderAdmin extends CoreAdmin
{
    /* @todo : remove this useless protected attributes */

    protected $baseRouteName = 'admin_librinfo_ecommerce_order';
    protected $baseRoutePattern = 'librinfo/ecommerce/order';
    protected $classnameLabel = 'Order';
    protected $datagridValues = [
        '_page'       => 1,
        '_sort_order' => 'DESC',
        '_sort_by'    => 'createdAt',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'show', 'batch', 'create'));
        $collection->add('updateShipping', $this->getRouterIdParameter() . '/updateShipping');
        $collection->add('updatePayment', $this->getRouterIdParameter() . '/updatePayment');
        $collection->add('cancelOrder', $this->getRouterIdParameter() . '/cancelOrder');
        $collection->add('validateOrder', $this->getRouterIdParameter() . '/validateOrder');
    }

    public function configureBatchActions($actions)
    {
        $actions = parent::configureBatchActions($actions);

        $actions['cancel'] = [
            'ask_confirmation' => true,
            'label'            => 'librinfo.label.cancel_order',
        ];

        $actions['validate'] = [
            'ask_confirmation' => true,
            'label'            => 'librinfo.label.fulfill_order',
        ];

        return $actions;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query
            ->addSelect('channel')
            ->leftJoin("$alias.channel", 'channel')
            ->addSelect('customer')
            ->leftJoin("$alias.customer", 'customer')
            ->andWhere("$alias.state != :state")
            ->setParameter('state', OrderInterface::STATE_CART);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if (isset($list['create'])) {
            // unset($list['create']);
        }

        return $list;
    }

    public function toString($object)
    {
        return $object->getNumber() ?: $object->getId();
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        $mapper
            ->tab('form_tab_general')
                ->with('form_group_general')
                    ->add('locale_code', HiddenType::class, [
                        'data' => $this->getConfigurationPool()->getContainer()->getParameter('locale'),
                    ])
                ->end()
            ->end()
        ;

        $mapper
            ->getFormBuilder()
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                $formData = $form->getData();

                if (isset($data['shippingAddress']) && isset($data['shippingAddress']['useSameAddressForBilling'])) {
                    if ((bool) $data['shippingAddress']['useSameAddressForBilling'] === true) {
                        $form->remove('billingAddress');
                        $form->add('billingAddress', OrderAddressType::class, [
                            'label'       => false,
                            'data_class'  => $this->getConfigurationPool()->getContainer()->getParameter('sylius.model.address.class'),
                            'mapped'      => false,
                            'constraints' => [],
                            'attr'        => [
                                'class' => 'nested-form',
                            ],
                            'validation_groups' => false,
                        ]);

                        $data['billingAddress'] = $data['shippingAddress'];
                        unset($data['billingAddress']['useSameAddressForBilling']);

                        $billingData = $formData->getBillingAddress();
                        foreach ($data['billingAddress'] as $field => $bData) {
                            try {
                                $propertyAccessor = new PropertyAccessor();
                                $propertyAccessor->setValue($billingData, $field, $bData);
                            } catch (NoSuchPropertyException $e) {
                            }
                        }

                        $formData->setBillingAddress($billingData);

                        $form->setData($formData);

                        $event->setData($data);
                    }
                }
            });
    }

    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        $factory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.address');
        $object->setShippingAddress($factory->createNew());
        $object->setBillingAddress($factory->createNew());

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        /** @var Librinfo\EcommerceBundle\Entity\Order $order */
        $order = $object;

        parent::prePersist($order);

        // Check http://docs.sylius.org/en/latest/book/orders/orders.html

        // @TODO: Add order shipment form and traitment
        //
        // /** @var ShipmentInterface $shipment */
        // $shipment = $this->container->get('sylius.factory.shipment')->createNew();
        //
        // $shipment->setMethod($this->container->get('sylius.repository.shipping_method')->findOneBy(['code' => 'UPS']));
        //
        // $order->addShipment($shipment);
        //
        // $this->container->get('sylius.order_processing.order_processor')->process($order);
        //
        // $stateMachineFactory = $this->container->get('sm.factory');
        //
        // $stateMachine = $stateMachineFactory->get($order, OrderShippingTransitions::GRAPH);
        // $stateMachine->apply(OrderShippingTransitions::TRANSITION_REQUEST_SHIPPING);

        // @TODO: Add order payment form and traitment
        //
        // /** @var PaymentInterface $payment */
        // $payment = $this->container->get('sylius.factory.payment')->createNew();
        //
        // $payment->setMethod($this->container->get('sylius.repository.payment_method')->findOneBy(['code' => 'offline']));
        //
        // $payment->setCurrencyCode($currencyCode);
        //
        // $order->addPayment($payment);
        //
        // $stateMachineFactory = $this->container->get('sm.factory');
        //
        // $stateMachine = $stateMachineFactory->get($order, OrderPaymentTransitions::GRAPH);
        // $stateMachine->apply(OrderPaymentTransitions::TRANSITION_REQUEST_PAYMENT);

        $order->setState(OrderInterface::STATE_NEW);
        $order->setShippingState(ShipmentInterface::STATE_READY);
        $order->setPaymentState(PaymentInterface::STATE_NEW);

        // @TODO: May be not usefull
        // $this->container->get('sylius.manager.order')->flush();
    }
}
