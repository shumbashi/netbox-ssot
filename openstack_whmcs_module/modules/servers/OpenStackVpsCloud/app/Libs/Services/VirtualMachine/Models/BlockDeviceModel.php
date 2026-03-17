<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;

/**
 * Class BlockDeviceModel
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Models\Vps
 */
class BlockDeviceModel extends BaseVpsModel
{
    const STATUS_AVAILABLE   = 'available';
    const STATUS_CREATING    = 'creating';
    const STATUS_DELETING    = 'deleting';
    const STATUS_DOWNLOADING = 'downloading';
    const STATUS_ERROR       = 'error';
    const STATUS_IN_USE      = 'in-use';
    const STATUS_ERROR_RESTORING = 'error_restoring';

    const ATTACH_DEVICE     = 'attachDevice';
    const ATTACH_DEVICE_VDA = 'vda';
    const DEV_PREFIX        = '/dev/';

    /**
     * @var string
     */
    protected $UUID;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $attachID;

    /**
     * @var string
     */
    protected $attachServer;

    /**
     * @var string
     */
    protected $attachDevice;

    /**
     * @var string
     */
    protected $bootable;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $created;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $imageRefID;

    /**
     * @var string
     */
    protected $volumeRefID;

    /**
     * @var string
     */
    protected $snapshotID;

    /**
     * BlockDeviceModel constructor.
     * @param string|null $tenantID
     * @param string|null $UUID
     * @param array $params
     */
    public function __construct(string $tenantID = null, string $UUID = null, array $params = [])
    {
        parent::__construct($tenantID, $UUID, $params);
        
    }

    /**
     * @return array
     */
    public function listSource()
    {
        return Api::getInstance()->volume()->listVolumes();
    }

    /**
     * Create volume
     */
    public function create()
    {
        $response = Api::getInstance()->volume()->createVolume(
            $this->size,
            $this->name,
            $this->imageRefID,
            $this->volumeRefID,
            $this->snapshotID,
            $this->bootable,
            $this->type
        );

        $this->UUID = $response['id'];
        $this->setDetails();

    }

    /**
     * @param array $details
     * @return bool
     * @throws Exception
     */
    public function setDetails(array $details = [])
    {
        if (empty($details))
        {
            try
            {
                $details = Api::getInstance()->volume()->getVolume($this->UUID);
            }
            catch (Exception $exception)
            {
                if ($exception->getCode() == 404)
                {
                    return false;
                }
                else
                {
                    throw $exception;
                }
            }
        }

        foreach ($details as $detailName => $value)
        {
            if ($detailName == self::ATTACH_DEVICE && strpos($value, self::DEV_PREFIX) === false && !empty($value))
            {
                $value = self::DEV_PREFIX . $value;
            }

            if (property_exists($this, $detailName))
            {
                $this->{$detailName} = $value;
            }
        }

        return true;
    }

    /**
     * Delete volume
     */
    public function delete()
    {
        try
        {
            $this->setDetails();
        }
        catch (OpenStackApiException $exception)
        {
            return;
        }

        if ($this->getStatus() == BlockDeviceModel::STATUS_DELETING)
        {
            return;
        }

        Api::getInstance()->volume()->deleteVolume($this->UUID);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Extend volume
     *
     * @param int $newSize
     */
    public function extend(int $newSize)
    {
        Api::getInstance()->volume()->extendVolume($this->UUID, $newSize);
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        return $this->UUID;
    }

    /**
     * @param string $UUID
     */
    public function setUUID(string $UUID)
    {
        $this->UUID = $UUID;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getAttachID()
    {
        return $this->attachID;
    }

    /**
     * @param string $attachID
     */
    public function setAttachID(string $attachID)
    {
        $this->attachID = $attachID;
    }

    /**
     * @return string
     */
    public function getAttachServer()
    {
        return $this->attachServer;
    }

    /**
     * @param string $attachServer
     */
    public function setAttachServer(string $attachServer)
    {
        $this->attachServer = $attachServer;
    }

    /**
     * @return string
     */
    public function getAttachDevice()
    {
        return $this->attachDevice;
    }

    /**
     * @param string $attachDevice
     */
    public function setAttachDevice(string $attachDevice)
    {
        $this->attachDevice = $attachDevice;
    }

    /**
     * @return bool
     */
    public function isBootable()
    {
        return $this->bootable == 'true' ? true : false;
    }

    /**
     * @param bool $bootable
     */
    public function setBootable(bool $bootable)
    {
        $this->bootable = $bootable;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(string $created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getImageRefID()
    {
        return $this->imageRefID;
    }

    /**
     * @param string $imageRefID
     */
    public function setImageRefID(string $imageRefID)
    {
        $this->imageRefID = $imageRefID;
    }

    /**
     * @return string
     */
    public function getVolumeRefID()
    {
        return $this->volumeRefID;
    }

    /**
     * @param string $volumeRefID
     */
    public function setVolumeRefID(string $volumeRefID)
    {
        $this->volumeRefID = $volumeRefID;
    }

    /**
     * @return string
     */
    public function getSnapshotID()
    {
        return $this->snapshotID;
    }

    /**
     * @param string $snapshotID
     */
    public function setSnapshotID(string $snapshotID)
    {
        $this->snapshotID = $snapshotID;
    }


}