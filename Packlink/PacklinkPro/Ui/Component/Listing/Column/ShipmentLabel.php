<?php
/**
 * @package    Packlink_PacklinkPro
 * @author     Packlink Shipping S.L.
 * @copyright  2019 Packlink
 */

namespace Packlink\PacklinkPro\Ui\Component\Listing\Column;

use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Packlink\PacklinkPro\Bootstrap;
use Packlink\PacklinkPro\Helper\UrlHelper;
use Packlink\PacklinkPro\IntegrationCore\BusinessLogic\Order\Interfaces\OrderRepository;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ServiceRegister;
use Packlink\PacklinkPro\Services\BusinessLogic\OrderRepositoryService;

/**
 * Class ShipmentLabel
 *
 * @package Packlink\PacklinkPro\Ui\Component\Listing\Column
 */
class ShipmentLabel extends Column
{
    /**
     * @var UrlHelper
     */
    private $urlHelper;
    /**
     * @var FormKey
     */
    private $formKey;

    /**
     * ShipmentLabel constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Bootstrap $bootstrap
     * @param UrlHelper $urlHelper
     * @param FormKey $formKey
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Bootstrap $bootstrap,
        UrlHelper $urlHelper,
        FormKey $formKey,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlHelper = $urlHelper;
        $this->formKey = $formKey;

        $bootstrap->initInstance();
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     *
     * @return array
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Exceptions\QueryFilterInvalidParamException
     * @throws \Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Exceptions\RepositoryNotRegisteredException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            /** @var OrderRepositoryService $orderRepositoryService */
            $orderRepositoryService = ServiceRegister::getService(OrderRepository::CLASS_NAME);

            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $orderDetails = $orderRepositoryService->getOrderDetailsById((int)$item['order_id']);
                if ($orderDetails === null || empty($orderDetails->getShipmentLabels())) {
                    continue;
                }

                $element = '';
                $shipmentLabel = $orderDetails->getShipmentLabels()[0];
                $controllerUrl = $this->urlHelper->getBackendUrl(
                    'packlink/shipmentlabels/shipmentlabels',
                    [
                        'ajax' => 1,
                        'form_key' => $this->formKey->getFormKey(),
                    ]
                );
                $printText = ($shipmentLabel->isPrinted() ? __('Printed') : __('Print'));
                $classList = (!$shipmentLabel->isPrinted() ? 'primary ' : '') . 'pl-print-label-button';

                $element .= '<button type="button" '
                    . 'data-link="' . $shipmentLabel->getLink() . '" '
                    . 'data-order-id="' . $orderDetails->getOrderId() . '" '
                    . 'data-controller-url="' . $controllerUrl . '" '
                    . 'class="' . $classList . '" '
                    . 'onclick="plPrintShipmentLabel(this)"'
                    . '>'
                    . $printText
                    . '</button>'
                    . '<div class="pl-printed-label" hidden>' . __('Printed') . '</div>';

                $item[$fieldName] = $element;
            }
        }

        return $dataSource;
    }
}
