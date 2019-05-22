<?php
/**
 * @package    Packlink_PacklinkPro
 * @author     Packlink Shipping S.L.
 * @copyright  2019 Packlink
 */

namespace Packlink\PacklinkPro\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Packlink\PacklinkPro\Bootstrap;
use Packlink\PacklinkPro\IntegrationCore\BusinessLogic\Order\Interfaces\OrderRepository;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ServiceRegister;
use Packlink\PacklinkPro\Services\BusinessLogic\OrderRepositoryService;

/**
 * Class SalesOrderPlaceAfter
 *
 * @package Packlink\PacklinkPro\Observer
 */
class SalesOrderPlaceAfter implements ObserverInterface
{
    /**
     * SalesOrderPlaceAfter constructor.
     *
     * @param \Packlink\PacklinkPro\Bootstrap $bootstrap
     */
    public function __construct(Bootstrap $bootstrap)
    {
        $bootstrap->initInstance();
    }

    /**
     * Handles event that is triggered after order has been placed.
     *
     * @param Observer $observer Magento observer.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Exceptions\QueryFilterInvalidParamException
     * @throws \Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Exceptions\RepositoryNotRegisteredException
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getOrder();

        if ($order->getShippingMethod() === null) {
            return;
        }

        /** @var OrderRepositoryService $orderRepositoryService */
        $orderRepositoryService = ServiceRegister::getService(OrderRepository::CLASS_NAME);
        $methodId = (int)$order->getShippingMethod(true)->getDataByKey('method');
        $dropOff = $orderRepositoryService->getDropOff($order, $methodId);

        if (!empty($dropOff)) {
            $orderRepositoryService->setSourceOrderShippingAddress($order, $dropOff);
        }
    }
}
