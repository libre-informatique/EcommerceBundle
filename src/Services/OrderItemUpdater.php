<?php
namespace Librinfo\EcommerceBundle\Services; 

use Doctrine\ORM\EntityManager;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;

/**
 * Manage order item quantity
 * 
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class OrderItemUpdater
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var OrderItemQuantityModifierInterface 
     */
    private $orderItemQuantityModifier;
    /**
     * @var MoneyFormatterInterface 
     */
    private $moneyFormatter;
    /**
     * @var String
     */
    private $orderItemClass;
    
    /**
     * 
     * @param EntityManager $em
     * @param OrderItemQuantityModifierInterface $quantityModifier
     * @param MoneyFormatterInterface $moneyFormatter
     * @param String $orderItemClass
     */
    public function __construct(EntityManager $em, OrderItemQuantityModifierInterface $quantityModifier, MoneyFormatterInterface $moneyFormatter, $orderItemClass)
    {
        $this->em = $em;
        $this->orderItemQuantityModifier = $quantityModifier;
        $this->moneyFormatter = $moneyFormatter;
        $this->orderItemClass = $orderItemClass;
    }
    
    /**
     * 
     * @param String $orderId
     * @param String $itemId
     * @param Bool $isAddition
     * @return Array
     */
    public function updateItemCount($orderId, $itemId, $isAddition)
    {
        $orderRepo = $this->em->getRepository('LibrinfoEcommerceBundle:Order');
        $itemRepo = $this->em->getRepository($this->orderItemClass);
        
        $order = $orderRepo->find($orderId);
        $item = $itemRepo->find($itemId);
 
        if($isAddition){
            $quantity = $item->getQuantity() + 1;
        } else {
            $quantity = $item->getQuantity() - 1;
        }
        
        $this->orderItemQuantityModifier->modify($item, $quantity);
        $item->recalculateUnitsTotal();
        $order->recalculateItemsTotal();
        
        $this->em->persist($order);
        $this->em->flush();

        return $this->formatArray($order, $item);
    }
    
    /**
     * 
     * @param String $order
     * @param String $item
     * @return Array
     */
    private function formatArray($order, $item)
    {
        return [
            'item' => [
                'quantity' => $item->getQuantity(),
                'total' => $this->moneyFormatter->format(
                    $item->getTotal(), 
                    $order->getCurrencyCode(), 
                    $order->getLocaleCode()
                ),
                'subtotal' => $this->moneyFormatter->format(
                    $item->getSubTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                )
            ],
            'order' => [
                'total' => $this->moneyFormatter->format(
                    $order->getTotal(), 
                    $order->getCurrencyCode(), 
                    $order->getLocaleCode()
                ),
                'items-total' => $this->moneyFormatter->format(
                    $order->getItemsTotal(), 
                    $order->getCurrencyCode(), 
                    $order->getLocaleCode()
                )
            ]
        ];
    }
}
