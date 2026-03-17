<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation;


class CreationBlockDevice extends Serializer
{
    const TYPE_VOLUME = 'volume';

    /**
     * @var string
     */
    protected $sourceType;

    /**
     * @var string
     */
    protected $destinationType;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var int
     */
    protected $bootIndex;
    /**
     * @var bool
     */
    protected $deleteOnTermination;
    /**
     * @var string
     */
    protected $deviceName;

    /**
     * @return int
     */
    public function getBootIndex(): int
    {
        return $this->bootIndex;
    }

    /**
     * @param int $bootIndex
     */
    public function setBootIndex(int $bootIndex)
    {
        $this->bootIndex = $bootIndex;
    }

    /**
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * @param string $sourceType
     */
    public function setSourceType(string $sourceType)
    {
        $this->sourceType = $sourceType;
    }

    /**
     * @return string
     */
    public function getDestinationType()
    {
        return $this->destinationType;
    }

    /**
     * @param string $destinationType
     */
    public function setDestinationType(string $destinationType)
    {
        $this->destinationType = $destinationType;
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        return $this->uuid;
    }

    /**
     * @param string $UUID
     */
    public function setUUID(string $UUID)
    {
        $this->uuid = $UUID;
    }

    /**
     * @return bool
     */
    public function isDeleteOnTermination()
    {
        return $this->deleteOnTermination;
    }

    /**
     * @param bool $deleteOnTermination
     */
    public function setDeleteOnTermination(bool $deleteOnTermination)
    {
        $this->deleteOnTermination = $deleteOnTermination;
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     */
    public function setDeviceName(string $deviceName)
    {
        $this->deviceName = $deviceName;
    }

}