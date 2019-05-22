<?php
/**
 * @package    Packlink_PacklinkPro
 * @author     Packlink Shipping S.L.
 * @copyright  2019 Packlink
 */

namespace Packlink\PacklinkPro\Controller\Adminhtml\Configuration;

use Magento\Framework\Controller\Result\JsonFactory;
use Packlink\PacklinkPro\Bootstrap;
use Packlink\PacklinkPro\IntegrationCore\BusinessLogic\Controllers\DashboardController;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ServiceRegister;

/**
 * Class Dashboard
 *
 * @package Packlink\PacklinkPro\Controller\Adminhtml\Configuration
 */
class Dashboard extends Configuration
{
    /**
     * Dashboard constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Packlink\PacklinkPro\Bootstrap $bootstrap
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Bootstrap $bootstrap,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context, $bootstrap, $jsonFactory);

        $this->allowedActions = ['getStatus'];
    }

    /**
     * Returns current setup status.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    protected function getStatus()
    {
        /** @var DashboardController $dashboardController */
        $dashboardController = ServiceRegister::getService(DashboardController::CLASS_NAME);
        $status = $dashboardController->getStatus();

        return $this->result->setData($status->toArray());
    }
}
