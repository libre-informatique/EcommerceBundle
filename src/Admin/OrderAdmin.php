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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\OrderPaymentTransitions;
use Sylius\Component\Core\OrderShippingTransitions;

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

                // dump($data,$formData);die;

                if (isset($data['channel'])) {
                    $channel = $this->getConfigurationPool()->getContainer()->get('sylius.repository.channel')->find($data['channel']);
                    $formData->setChannel($channel);
                }

                if (isset($data['shippingAddress']) && isset($data['shippingAddress']['email'])) {
                    $formData->getCustomer()->setEmail($data['shippingAddress']['email']);
                }

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

                if (isset($data['payment'])) {
                    $paymentCode = $data['payment'];

                    $payment = $this->getConfigurationPool()->getContainer()->get('sylius.factory.payment')->createNew();
                    $currency = $this->getConfigurationPool()->getContainer()->get('sylius.repository.currency')->find($data['currency_code']);

                    $payment->setMethod($this->getConfigurationPool()->getContainer()->get('sylius.repository.payment_method')->findOneBy(['code' => $paymentCode]));
                    $payment->setCurrencyCode($currency->getCode());
                    $payment->setState(PaymentInterface::STATE_NEW);

                    $formData->addPayment($payment);
                }

                if (isset($data['shipment'])) {
                    $shipmentCode = $data['shipment'];

                    $shipment = $this->getConfigurationPool()->getContainer()->get('sylius.factory.shipment')->createNew();

                    $shipment->setMethod($this->getConfigurationPool()->getContainer()->get('sylius.repository.shipping_method')->findOneBy(['code' => $shipmentCode]));
                    $shipment->setState(ShipmentInterface::STATE_READY);

                    $formData->addShipment($shipment);
                }
            })
        ;
    }

    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        $addressFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.address');
        $customerFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.customer');

        $object->setShippingAddress($addressFactory->createNew());
        $object->setBillingAddress($addressFactory->createNew());
        $object->setCustomer($customerFactory->createNew());

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
        $this->getConfigurationPool()->getContainer()->get('librinfo_ecommerce.order_customer_manager')->associateUserAndAddress($order);

        $order->setCheckoutCompletedAt(new \DateTime('NOW'));

        $order->setState(OrderInterface::STATE_NEW);
        // $order->setPaymentState(PaymentInterface::STATE_NEW);
        // $order->setShippingState(ShipmentInterface::STATE_READY);

        $stateMachineFactory = $this->getConfigurationPool()->getContainer()->get('sm.factory');
        //
        $payment = clone $order->getPayments()->first();
        $shipment = clone $order->getShipments()->first();

        $order->getPayments()->clear();
        $order->getShipments()->clear();

        $this->getConfigurationPool()->getContainer()->get('sylius.repository.order')->add($order);

        $orderProcessor = $this->getConfigurationPool()->getContainer()->get('sylius.order_processing.order_processor');
        $orderProcessor->process($order);

        $order->addPayment($payment);
        $order->addShipment($shipment);

        $stateMachine = $stateMachineFactory->get($order, OrderShippingTransitions::GRAPH);
        $stateMachine->apply(OrderShippingTransitions::TRANSITION_REQUEST_SHIPPING);

        $stateMachine = $stateMachineFactory->get($order, OrderPaymentTransitions::GRAPH);
        $stateMachine->apply(OrderPaymentTransitions::TRANSITION_REQUEST_PAYMENT);

        $this->getConfigurationPool()->getContainer()->get('sylius.manager.order')->flush($order);
    }
}
