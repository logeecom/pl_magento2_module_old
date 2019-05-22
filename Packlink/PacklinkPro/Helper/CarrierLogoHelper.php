<?php
/**
 * @package    Packlink_PacklinkPro
 * @author     Packlink Shipping S.L.
 * @copyright  2019 Packlink
 */

namespace Packlink\PacklinkPro\Helper;

use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\View\Asset\Repository;
use Packlink\PacklinkPro\Bootstrap;
use Packlink\PacklinkPro\IntegrationCore\BusinessLogic\Configuration;
use Packlink\PacklinkPro\IntegrationCore\BusinessLogic\ShippingMethod\Models\ShippingMethod;
use Packlink\PacklinkPro\IntegrationCore\BusinessLogic\ShippingMethod\ShippingMethodService;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ServiceRegister;
use Packlink\PacklinkPro\Services\BusinessLogic\ConfigurationService;

/**
 * Class CarrierLogoHelper
 *
 * @package Packlink\PacklinkPro\Helper
 */
class CarrierLogoHelper
{
    /**
     * @var Reader
     */
    private $moduleReader;
    /**
     * @var Repository
     */
    protected $assetRepo;

    /**
     * CarrierLogoHelper constructor.
     *
     * @param Reader $reader
     * @param Bootstrap $bootstrap
     * @param Repository $assetRepo
     */
    public function __construct(Reader $reader, Bootstrap $bootstrap, Repository $assetRepo)
    {
        $this->moduleReader = $reader;
        $this->assetRepo = $assetRepo;

        $bootstrap->initInstance();
    }

    /**
     * Returns carrier logo file path of shipping method with provided ID.
     * If logo doesn't exist returns default carrier logo.
     *
     * @param int $shippingMethodId ID of the shipping method.
     *
     * @return string Logo file path.
     */
    public function getCarrierLogoFilePath($shippingMethodId)
    {
        /** @var ConfigurationService $configService */
        $configService = ServiceRegister::getService(Configuration::CLASS_NAME);
        $userInfo = $configService->getUserInfo();

        if ($userInfo === null) {
            return $this->assetRepo->getUrl('Packlink_PacklinkPro::images/carriers/carrier.jpg');
        }

        $this->assetRepo->getUrl('Packlink_PacklinkPro::images/logo.png');
        $carrierLogoDir = $this->moduleReader->getModuleDir(
            Dir::MODULE_VIEW_DIR,
            'Packlink_PacklinkPro'
        ) . '/adminhtml/web/images/carriers/' . strtolower($userInfo->country);

        /** @var ShippingMethodService $shippingMethodService */
        $shippingMethodService = ServiceRegister::getService(ShippingMethodService::CLASS_NAME);
        /** @var ShippingMethod $shippingMethod */
        $shippingMethod = $shippingMethodService->getShippingMethod($shippingMethodId);

        $carrierLogoFile = strtolower(str_replace(' ', '-', $shippingMethod->getCarrierName())) . '.png';

        $logoPath = $carrierLogoDir . '/' . $carrierLogoFile;
        if (!file_exists($logoPath)) {
            return $this->getDefaultCarrierLogoPath();
        }

        return $this->assetRepo->getUrl(
            'Packlink_PacklinkPro::images/carriers/' . strtolower($userInfo->country) . '/' . $carrierLogoFile
        );
    }

    /**
     * Returns path to default carrier logo.
     *
     * @return string
     */
    public function getDefaultCarrierLogoPath()
    {
        return $this->assetRepo->getUrl('Packlink_PacklinkPro::images/carriers/carrier.jpg');
    }
}
