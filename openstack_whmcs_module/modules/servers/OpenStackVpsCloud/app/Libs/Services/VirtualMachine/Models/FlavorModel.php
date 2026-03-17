<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;


use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Api\OpenStackVPS\ComputeApiService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

/**
 * Class FlavorModel
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models
 */
class FlavorModel extends BaseVpsModel
{
    const DISK        = 'disk';
    const RAM         = 'ram';
    const VCPUS       = 'vcpus';
    const RXTX_FACTOR = 'rxtx_factor';

    const NAME_NONE = 'none';
    /**
     * @var string
     */
    protected $name = self::NAME_NONE;
    /**
     * @var int
     */
    protected $disk;
    /**
     * @var int
     */
    protected $ram;
    /**
     * @var int
     */
    protected $vcpus;
    /**
     * @var bool
     */
    protected $public;
    /**
     * @var int
     */
    protected $rxtxFactor;

    /**
     * FlavorModel constructor.
     * @param string $tenantID
     * @param string $id
     * @param array $params
     * @throws Exception
     */
    public function __construct(string $tenantID, string $id = null, array $params = [])
    {

        if (!isset($params['public']) && $id)
        {
            $params = Api::getInstance()->compute()->getFlavor($id);
        }

        parent::__construct($tenantID, $id, $params);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function listSource()
    {
        return Api::getInstance()->compute()->listFlavors();
    }

    /**
     * @throws Exception
     */
    public function create()
    {
        $this->UUID = Api::getInstance()->compute()->createFlavor(
            $this->name,
            [
                self::DISK        => $this->disk,
                self::RAM         => $this->ram,
                self::VCPUS       => $this->vcpus,
                self::RXTX_FACTOR => $this->rxtxFactor,
            ],
            (bool)$this->public
        );

        Api::getInstance()->compute()->addFlavorAccess($this->UUID, $this->tenantID);
    }

    public function setExtraSpecs($specs)
    {
        Api::getInstance()->compute()->createExtraSpecsForFlavor($this->getUUID(), $specs);
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        Api::getInstance()->compute()->deleteFlavor($this->UUID);
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
     * @return int
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param int $disk
     */
    public function setDisk(?int $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @return int
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * @param int $ram
     */
    public function setRam(?int $ram)
    {
        $this->ram = $ram;
    }

    /**
     * @return int
     */
    public function getVcpus()
    {
        return $this->vcpus;
    }

    /**
     * @param int $vcpus
     */
    public function setVcpus(?int $vcpus)
    {
        $this->vcpus = $vcpus;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param bool $public
     */
    public function setPublic(bool $public)
    {
        $this->public = $public;
    }

    /**
     * @return int
     */
    public function getRxtxFactor()
    {
        return $this->rxtxFactor;
    }

    /**
     * @param int $rxtxFactor
     */
    public function setRxtxFactor(int $rxtxFactor)
    {
        $this->rxtxFactor = $rxtxFactor;
    }


}
