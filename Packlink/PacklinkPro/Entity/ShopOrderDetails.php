<?php
/**
 * @package    Packlink_PacklinkPro
 * @author     Packlink Shipping S.L.
 * @copyright  2019 Packlink
 */

namespace Packlink\PacklinkPro\Entity;

use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Configuration\EntityConfiguration;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Configuration\IndexMap;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ORM\Entity;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\ServiceRegister;
use Packlink\PacklinkPro\IntegrationCore\Infrastructure\Utility\TimeProvider;
use Packlink\PacklinkPro\Model\ShipmentLabel;

/**
 * Class ShopOrderDetails
 *
 * @package Packlink\PacklinkPro\Entity
 */
class ShopOrderDetails extends Entity
{
    /**
     * Fully qualified name of this class.
     */
    const CLASS_NAME = __CLASS__;

    /**
     * Array of field names.
     *
     * @var array
     */
    protected $fields = [
        'id',
        'orderId',
        'shipmentReference',
        'dropOffId',
        'shipmentLabels',
        'status',
        'lastStatusUpdateTime',
        'carrierTrackingNumbers',
        'carrierTrackingUrl',
        'packlinkShippingPrice',
        'taskId',
        'deleted',
    ];
    /**
     * Shop order ID.
     *
     * @var int
     */
    private $orderId;
    /**
     * Shipment reference.
     *
     * @var string
     */
    private $shipmentReference;
    /**
     * Drop off location ID.
     *
     * @var int
     */
    private $dropOffId;
    /**
     * Order shipment labels.
     *
     * @var ShipmentLabel[]
     */
    private $shipmentLabels;
    /**
     * Tracking status.
     *
     * @var string
     */
    private $status;
    /**
     * Date and time of last status update.
     *
     * @var \DateTime
     */
    private $lastStatusUpdateTime;
    /**
     * Array of carrier tracking numbers.
     *
     * @var array
     */
    private $carrierTrackingNumbers;
    /**
     * Packlink tracking URL.
     *
     * @var string
     */
    private $carrierTrackingUrl;
    /**
     * Packlink shipping price.
     *
     * @var float
     */
    private $packlinkShippingPrice;
    /**
     * Identifier of corresponding SendDraftTask.
     *
     * @var int
     */
    private $taskId;
    /**
     * Is associated shipment on Packlink has been deleted.
     *
     * @var bool
     */
    private $deleted;

    /**
     * Returns entity configuration object.
     *
     * @return EntityConfiguration Configuration object.
     */
    public function getConfig()
    {
        $map = new IndexMap();

        $map->addIntegerIndex('orderId');
        $map->addStringIndex('shipmentReference');
        $map->addStringIndex('status');

        return new EntityConfiguration($map, 'ShopOrderDetails');
    }

    /**
     * Sets raw array data to this entity instance properties.
     *
     * @param array $data Raw array data with keys for class fields. @see self::$fields for field names.
     *
     * @throws \Exception
     */
    public function inflate(array $data)
    {
        /** @var TimeProvider $timeProvider */
        $timeProvider = ServiceRegister::getService(TimeProvider::CLASS_NAME);

        foreach ($this->fields as $fieldName) {
            if ($fieldName === 'shipmentLabels' && !empty($data['shipmentLabels'])) {
                $this->shipmentLabels = ShipmentLabel::fromArrayBatch($data['shipmentLabels']);
            } elseif ($fieldName === 'lastStatusUpdateTime' && !empty($data['lastStatusUpdateTime'])) {
                $this->lastStatusUpdateTime = $timeProvider->getDateTime($data['lastStatusUpdateTime']);
            } else {
                $this->$fieldName = static::getArrayValue($data, $fieldName);
            }
        }
    }

    /**
     * Transforms entity to its array format representation.
     *
     * @return array Entity in array format.
     */
    public function toArray()
    {
        $data = [];

        foreach ($this->fields as $fieldName) {
            if ($fieldName === 'shipmentLabels' && $this->shipmentLabels !== null) {
                foreach ($this->shipmentLabels as $shipmentLabel) {
                    $data['shipmentLabels'][] = $shipmentLabel->toArray();
                }
            } elseif ($fieldName === 'lastStatusUpdateTime') {
                $data[$fieldName] = $this->lastStatusUpdateTime ? $this->lastStatusUpdateTime->getTimestamp() : null;
            } else {
                $data[$fieldName] = $this->$fieldName;
            }
        }

        return $data;
    }

    /**
     * Returns order ID.
     *
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Sets order ID.
     *
     * @param int $orderId ID of the order.
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Returns shipment reference.
     *
     * @return string
     */
    public function getShipmentReference()
    {
        return $this->shipmentReference;
    }

    /**
     * Sets order shipping reference.
     *
     * @param string $reference Shipping reference.
     */
    public function setShipmentReference($reference)
    {
        $this->shipmentReference = $reference;
    }

    /**
     * Returns order shipment labels.
     *
     * @return ShipmentLabel[]
     */
    public function getShipmentLabels()
    {
        return $this->shipmentLabels ?: [];
    }

    /**
     * Sets order shipment labels from array of links to PDF.
     *
     * @param array $links Array of links to PDF.
     */
    public function setShipmentLabels(array $links)
    {
        if (empty($this->shipmentLabels)) {
            $shipmentLabels = [];

            foreach ($links as $link) {
                $shipmentLabels[] = new ShipmentLabel($link);
            }

            $this->shipmentLabels = $shipmentLabels;
        }
    }

    /**
     * Returns order status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets order status.
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns order shipping status.
     *
     * @return string
     */
    public function getShippingStatus()
    {
        return $this->status ?: '';
    }

    /**
     * Sets order shipping status.
     *
     * @param string $status Order shipping status.
     * @param int $updateTime Last shipping status update timestamp.
     */
    public function setShippingStatus($status, $updateTime = null)
    {
        /** @var TimeProvider $timeProvider */
        $timeProvider = ServiceRegister::getService(TimeProvider::CLASS_NAME);

        $this->status = $status;

        if ($updateTime === null) {
            $this->lastStatusUpdateTime = $timeProvider->getCurrentLocalTime();
        } else {
            $this->lastStatusUpdateTime = $timeProvider->getDateTime($updateTime);
        }
    }

    /**
     * Returns array of carrier tracking numbers.
     *
     * @return array
     */
    public function getCarrierTrackingNumbers()
    {
        return $this->carrierTrackingNumbers ?: [];
    }

    /**
     * Sets carrier tracking numbers.
     *
     * @param array $carrierTrackingNumbers Array of carrier tracking numbers.
     */
    public function setCarrierTrackingNumbers($carrierTrackingNumbers)
    {
        $this->carrierTrackingNumbers = $carrierTrackingNumbers;
    }

    /**
     * Returns last status update time.
     *
     * @return \DateTime
     */
    public function getLastStatusUpdateTime()
    {
        return $this->lastStatusUpdateTime;
    }

    /**
     * Returns Packlink shipping price.
     *
     * @return float
     */
    public function getPacklinkShippingPrice()
    {
        return $this->packlinkShippingPrice;
    }

    /**
     * Sets Packlink shipping price.
     *
     * @param float $packlinkShippingPrice
     */
    public function setPacklinkShippingPrice($packlinkShippingPrice)
    {
        $this->packlinkShippingPrice = $packlinkShippingPrice;
    }

    /**
     * Returns drop-off identifier.
     *
     * @return int
     */
    public function getDropOffId()
    {
        return $this->dropOffId;
    }

    /**
     * Sets drop-off identifier.
     *
     * @param int $dropOffId
     */
    public function setDropOffId($dropOffId)
    {
        $this->dropOffId = $dropOffId;
    }

    /**
     * Returns carrier tracking URL.
     *
     * @return string
     */
    public function getCarrierTrackingUrl()
    {
        return $this->carrierTrackingUrl;
    }

    /**
     * Sets carrier tracking URL.
     *
     * @param string $carrierTrackingUrl
     */
    public function setCarrierTrackingUrl($carrierTrackingUrl)
    {
        $this->carrierTrackingUrl = $carrierTrackingUrl;
    }

    /**
     * Returns identifier of corresponding SendDraftTask.
     *
     * @return int
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Sets identifier of corresponding SendDraftTask.
     *
     * @param int $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * Returns whether associated shipment on Packlink has been deleted.
     *
     * @return bool TRUE if shipment has been deleted, otherwise FALSE.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Sets whether associated shipment on Packlink has been deleted.
     *
     * @param bool $deleted TRUE if shipment has been deleted, otherwise FALSE.
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }
}
