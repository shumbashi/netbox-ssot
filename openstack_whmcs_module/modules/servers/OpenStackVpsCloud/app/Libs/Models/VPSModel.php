<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Models;


use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\ImageModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\KeyPairModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\NetworkAddressModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmService;

/**
 * Class VPSModel
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Models
 */

class VPSModel extends Model
{
    const STATUS_NOT_CREATED = 'NOTCREATED';
    const STATUS_ERROR       = 'ERROR';
    const STATUS_ACTIVE      = 'ACTIVE';
    const STATUS_SHUT_OFF    = 'SHUTOFF';
    const STATUS_PAUSED      = 'PAUSED';
    const STATUS_SUSPENDED   = 'SUSPENDED';
    const STATUS_RESCUE      = 'RESCUE';
    const STATUS_RESIZE      = 'RESIZE';
    const STATUS_RESIZED     = 'RESIZED';
    const STATUS_REBUILD     = 'REBUILD';
    const STATUS_VERIFY_RESIZE = 'VERIFY_RESIZE';

    /**
     * @var string
     */
    protected $status = self::STATUS_NOT_CREATED;

    /**
     * @var string
     */
    protected $stateTask = null;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $dateCreated;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var FlavorModel
     */
    protected $flavor;

    /**
     * @var ImageModel
     */
    protected $image;

    protected ?array $addresses = [];

    /**
     * @var string|null
     */
    protected $creationPortID;

    /**
     * @var array
     */
    protected $creationPortsIDs = [];

    /**
     * @var array
     */
    protected $backups;

    /**
     * @var array
     */
    protected $securityGroup;

    /**
     * @var KeyPairModel
     */
    protected $sshKey = null;

    /**
     * @var BlockDeviceModel[]
     */
    protected $blockDevices = [];

    /**
     * @var bool
     */
    protected $useDeviceName = false;

    /**
     * @var string
     */
    protected $customScript;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $availability_zone;

    /**
     * VPSModel constructor.
     * @param string $tenantID
     * @param string|null $vpsID
     * @param array $vpsDetails
     * @throws \Exception
     */
    public function __construct(string $tenantID, string $vpsID = null, array $vpsDetails = [])
    {
        parent::__construct($tenantID, $vpsID, $vpsDetails);

        if ($vpsID && strtolower($vpsID) !== "new")
        {
            $this->UUID = $vpsID;
            $this->setDetails();
        }
    }

    /**
     * @throws \Exception
     */
    public function setDetails()
    {
        $vpsDetails = Api::getInstance()->compute()->getVPSDetails($this->UUID);

        foreach ($vpsDetails as $name => $value)
        {
            if (property_exists($this, $name))
            {
                $this->{$name} = $value;
            }
        }

        if (isset($vpsDetails['flavorID'])) {
            $this->setFlavorFromVpsDetails($vpsDetails['flavorID']);
        }

        $this->setImageFromVpsDetails($vpsDetails['imageID']);

        if ($vpsDetails['keyName'])
        {
            $this->setKeyNameFromVpsDetails($vpsDetails['keyName']);
        }

        if ($vpsDetails['blockDevicesList'])
        {
            $this->setBlockDevicesFromVpsDetails($vpsDetails['blockDevicesList']);
        }
    }

    /**
     * @param string $flavorID
     * @throws \Exception
     */
    protected function setFlavorFromVpsDetails(string $flavorID)
    {
        try
        {
            $this->flavor = new FlavorModel($this->tenantID, $flavorID);
        }
        catch (\Exception $e)
        {
            if ($e->getCode() == 404)
            {
                $this->flavor = new FlavorModel($this->tenantID);
            }
            else
            {
                throw $e;
            }
        }
    }

    /**
     * @param string $imageID
     * @throws \Exception
     */
    protected function setImageFromVpsDetails(string $imageID)
    {
        try
        {
            $this->image = new ImageModel($this->tenantID, $imageID);
        }
        catch (\Exception $e)
        {
            if ($e->getCode() == 404)
            {
                $this->image = new ImageModel($this->tenantID);
            }
            else
            {
                throw $e;
            }
        }
    }

    /**
     * @param string $keyName
     * @throws \Exception
     */
    protected function setKeyNameFromVpsDetails(string $keyName)
    {
        /**
         * Current private key if exist. Api does not return the private key
         */
        $privateKey = $this->getSshKey() ? $this->getSshKey()->getPrivate() : null;

        try
        {
            $this->sshKey = new KeyPairModel($this->tenantID, $keyName);
            $this->sshKey->setPrivate($privateKey);
        }
        catch (\Exception $e)
        {
            if ($e->getCode() == 404)
            {
                $this->sshKey = new KeyPairModel($this->tenantID);
                $this->sshKey->setPrivate($privateKey);
            }
            else
            {
                throw $e;
            }
        }
    }

    /**
     * @return KeyPairModel
     */
    public function getSshKey()
    {
        return $this->sshKey;
    }

    /**
     * @param KeyPairModel|null $sshKey
     */
    public function setSshKey(KeyPairModel $sshKey = null)
    {
        $this->sshKey = $sshKey;
    }

    /**
     * @param array $blockDevicesList
     */
    protected function setBlockDevicesFromVpsDetails(array $blockDevicesList)
    {
        foreach ($blockDevicesList as $device)
        {
            $tmp = new BlockDeviceModel($this->tenantID, $device, ['UUID' => $device]);
            $tmp->setDetails();
            $this->blockDevices[$tmp->getAttachID()] = $tmp;
        }
    }

    /**
     * Return array of VMs with VM ID as array key
     *
     * @return array|null
     */
    public function listSource()
    {
        if (is_null($this->UUID)) {
            return Api::getInstance()->compute()->VPSDetailedList();
        } else {
            return [Api::getInstance()->compute()->getVPSDetails($this->UUID)];
        }
    }

    /**
     * @throws \Exception
     */
    public function checkStateTask()
    {
        $wait       = 1;
        $maxMinutes = 0.5;

        $maxNum = $maxMinutes * 60 / $wait;
        $num    = 0;

        do
        {
            sleep($wait);
            $num++;
            $this->setDetails();
        } while ($this->stateTask && $num < $maxNum);
    }

    /**
     * @param BlockDeviceModel $device
     * @return bool
     */
    public function addBlockDevice(BlockDeviceModel $device)
    {
        $this->blockDevices[$device->getUUID()] = $device;

        if (empty($this->UUID))
        {
            return true;
        }
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
     * @return mixed
     */
    public function getStateTask()
    {
        return $this->stateTask;
    }

    /**
     * @param mixed $stateTask
     */
    public function setStateTask($stateTask)
    {
        $this->stateTask = $stateTask;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAvailabilityZone()
    {
        return $this->availability_zone;
    }

    /**
     * @param mixed $availability_zone
     */
    public function setAvailabilityZone($availability_zone)
    {
        $this->availability_zone = $availability_zone;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return FlavorModel
     */
    public function getFlavor()
    {
        return $this->flavor;
    }

    /**
     * @param FlavorModel $flavor
     * @param array $params
     * @throws Exception
     * @throws OSException
     */
    public function setFlavor(FlavorModel $flavor, array $params = [])
    {
        if (null == $this->flavor || $this->flavor->getUUID() == $flavor->getUUID())
        {
            $this->flavor = $flavor;
            return;
        }

        $oldFlavorId  = $this->flavor->getUUID();
        $this->flavor = $flavor;
        $vmService    = new VmService($params);
        $vmService->changeFlavor($this, $oldFlavorId);

    }

    /**
     * @return ImageModel
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ImageModel $image
     */
    public function setImage(ImageModel $image)
    {
        $this->image = $image;
    }

    /**
     * @return NetworkAddressModel[]
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @param array $interfaces
     */
    public function setInterfaces(array $interfaces = [])
    {
        $this->interfaces = $interfaces;
    }

    /**
     * @param bool $force
     * @return array
     */
    public function getBackups(bool $force = false)
    {
        return $this->backups;
    }

    /**
     * @param array $backups
     */
    public function setBackups(array $backups)
    {
        $this->backups = $backups;
    }

    /**
     * @return array
     */
    public function getSecurityGroup()
    {
        return $this->securityGroup;
    }

    /**
     * @param array $securityGroup
     */
    public function setSecurityGroup(array $securityGroup)
    {
        $this->securityGroup = $securityGroup;
    }

    /**
     * @return null
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param null $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return BlockDeviceModel[]
     */
    public function getBlockDevices()
    {
        return $this->blockDevices;
    }

    /**
     * @param BlockDeviceModel[]
     */
    public function setBlockDevices(array $blockDevices)
    {
        $this->blockDevices = $blockDevices;
    }

    /**
     * @return mixed
     */
    public function getUUID()
    {
        return $this->UUID;
    }

    /**
     * @param mixed $UUID
     */
    public function setUUID($UUID)
    {
        $this->UUID = $UUID;
    }

    /**
     * @return string
     */
    public function getTenantID(): string
    {
        return $this->tenantID;
    }

    /**
     * @param string $tenantID
     */
    public function setTenantID(string $tenantID)
    {
        $this->tenantID = $tenantID;
    }

    /**
     * @return bool
     */
    public function isUseDeviceName()
    {
        return $this->useDeviceName;
    }

    /**
     * @param bool $useDeviceName
     */
    public function setUseDeviceName(bool $useDeviceName)
    {
        $this->useDeviceName = $useDeviceName;
    }

    /**
     * @return string
     */
    public function getCustomScript()
    {
        return $this->customScript;
    }

    /**
     * @param string|null $customScript
     */
    public function setCustomScript(string $customScript = null)
    {
        $this->customScript = $customScript;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array|null $metadata
     */
    public function setMetadata($metadata = null)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getCreationPortsIDs(): array
    {
        return $this->creationPortsIDs;
    }

    public function setCreationPortsIDs(array $creationPortsIDs): void
    {
        $this->creationPortsIDs = $creationPortsIDs;
    }

    /**
     * @param array $creationPortsIDs
     */
    public function addCreationPortID(string $creationPortsIDs): void
    {
        $this->creationPortsIDs[] = $creationPortsIDs;
    }

    public function jsonSerialize()
    {
        $array = parent::jsonSerialize();

        $array['password'] = \encrypt($array['password']);
        $array['customScript'] = \encrypt($array['customScript']);

        return $array;
    }

    public static function fromArray(?array $array)
    {
        $model = parent::fromArray($array);

        $model->interfaces = [];
        foreach ($array['addresses'] ?? [] as $address) {
            $model->addresses[] = NetworkAddressModel::fromArray($address);
        }

        foreach ($array['blockDevices'] ?? [] as $deviceId => $device) {
            $model->blockDevices[$deviceId] = BlockDeviceModel::fromArray($device);
        }

        if (!empty($array['flavor'])) {
            $model->flavor = FlavorModel::fromArray($array['flavor']);
        }

        if (!empty($array['sshKey'])) {
            $model->sshKey = KeyPairModel::fromArray($array['sshKey']);
        }

        if (!empty($array['image'])) {
            $model->image = ImageModel::fromArray($array['image']);
        }

        $model->password = \decrypt($array['password']);
        $model->customScript = \decrypt($array['customScript']);

        return $model;
    }
}