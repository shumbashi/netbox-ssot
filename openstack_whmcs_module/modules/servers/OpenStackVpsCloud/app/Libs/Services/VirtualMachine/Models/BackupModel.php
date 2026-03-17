<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;

class BackupModel extends BaseVpsModel
{
    const TYPE_WEEKLY = 'weekly';
    const TYPE_DAILY  = 'daily';
    const NEW         = 'NEW';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var VPSModel
     */
    protected $sourceVPS = null;

    /**
     * @var string
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $created;

    /**
     * @var VPSModel
     */
    protected $parentVM;

    /**
     * @var string
     */
    protected $status;
    /**
     * BackupModel constructor.
     * @param string $tenantID
     * @param string|null $UUID
     * @param array $vpsDetail
     */
    public function __construct(string $tenantID, string $UUID = null, array $vpsDetail = [])
    {
        parent::__construct($tenantID, $UUID, $vpsDetail);


        if (isset($vpsDetail['source']['id']))
        {
            $this->sourceVPS = $vpsDetail['source']['id'];
            $this->type      = $vpsDetail['source']['backupType'];
        }
    }

    /**
     * @param string $vpsID
     * @return array
     * @throws OSException
     */
    public static function listSource(string $vpsID)
    {
        $backupsList = [];
        foreach (Api::getInstance()->image()->listBackups() as $backup)
        {
            if ($backup['source'] == $vpsID)
            {
                $backupsList[] = $backup;
            }
        }
        return $backupsList;
    }

    /**
     * @param string|null $name
     * @param string $type
     * @param int $routing
     * @throws Exception
     */
    public function create(string $name = null, string $type = self::TYPE_WEEKLY, int $routing = 2)
    {
        $this->name = $name ?: $this->name;

        Api::getInstance()->compute()->createBackup($this->sourceVPS->UUID, $this->name, $type, $routing);
    }

    /**
     * @throws Exception
     */
    public function delete()
    {
        Api::getInstance()->image()->deleteImage($this->UUID);
    }

    /**
     * @param $adminPassword
     * @throws \ModulesGarden\OpenStackVpsCloud\Core\HandlerError\Exceptions\Exception
     */
    public function restore($adminPassword)
    {
        if (empty($this->parentVM->getUUID()))
        {
            throw new \Exception('This object is not properly loaded');
        }

        Api::getInstance()->compute()->rebuild($this->parentVM->getUUID(), $this->parentVM->getName(), $adminPassword, $this->UUID);
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
     * @return mixed
     */
    public function getSourceVPS()
    {
        return $this->sourceVPS;
    }

    /**
     * @param mixed $sourceVPS
     */
    public function setSourceVPS($sourceVPS)
    {
        $this->sourceVPS = $sourceVPS;
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
     * @return VPSModel
     */
    public function getParentVM(): VPSModel
    {
        return $this->parentVM;
    }

    /**
     * @param VPSModel $parentVM
     */
    public function setParentVM(VPSModel $parentVM)
    {
        $this->parentVM = $parentVM;
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