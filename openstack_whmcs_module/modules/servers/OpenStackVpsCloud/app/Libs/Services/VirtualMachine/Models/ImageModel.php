<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

/**
 * Class ImageModel
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Models\Vps
 */
class ImageModel extends BaseVpsModel
{
    const NAME_NONE          = 'none';
    const VISIBILITY_PUBLIC  = 'public';
    const VISIBILITY_PRIVATE = 'private';

    /**
     * @var string
     */
    protected $UUID;

    /**
     * @var string
     */
    protected $name = self::NAME_NONE;

    /**
     * @var string
     */
    protected $visibility = self::VISIBILITY_PUBLIC;

    /**
     * @var int
     */
    protected $minDisk;

    /**
     * @var int
     */
    protected $minRam;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $diskFormat;

    /**
     * @var string
     */
    protected $status;

    /**
     * ImageModel constructor.
     * @param string|null $tenantID
     * @param string|null $UUID
     * @param array $params
     */
    public function __construct(string $tenantID = null, string $UUID = null, array $params = [])
    {
        if (empty($params) && $UUID)
        {
            $params = Api::getInstance()->image()->getImage($UUID);
        }

        parent::__construct($tenantID, $UUID, $params);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function listSource()
    {
        $images          = Api::getInstance()->image()->listImages();

        foreach ($images as &$image)
        {
            $image['visibility'] = $image['visibility'] ?: self::VISIBILITY_PRIVATE;
        }

        return $images;
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
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility(string $visibility)
    {
        $this->visibility = $visibility;
    }

    /**
     * @return int
     */
    public function getMinDisk()
    {
        return $this->minDisk;
    }

    /**
     * @param int $minDisk
     */
    public function setMinDisk(int $minDisk)
    {
        $this->minDisk = $minDisk;
    }

    /**
     * @return int
     */
    public function getMinRam()
    {
        return $this->minRam;
    }

    /**
     * @param int $minRam
     */
    public function setMinRam(int $minRam)
    {
        $this->minRam = $minRam;
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
    public function getDiskFormat()
    {
        return $this->diskFormat;
    }

    /**
     * @param string $diskFormat
     */
    public function setDiskFormat(string $diskFormat)
    {
        $this->diskFormat = $diskFormat;
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


}