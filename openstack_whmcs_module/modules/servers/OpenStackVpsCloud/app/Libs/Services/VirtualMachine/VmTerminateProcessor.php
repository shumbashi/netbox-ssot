<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine;


use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeleteBlockDevices;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeleteDetails;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeleteVolumes;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeleteNetworking;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\TerminatingAccount;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\TerminationVM;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\BackupFactory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\App\Models\Keypairs;
use ModulesGarden\OpenStackVpsCloud\App\Models\Settings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class VmTerminateProcessor
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    /**
     * @var ProductCustomFields
     */
    protected $productCustomFields;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * VmTerminateProcessor constructor.
     * @param VPSModel $vm
     * @param int $serviceId
     */
    public function __construct(VPSModel $vm, int $serviceId)
    {
        $this->params              = WhmcsParamsHelper::getWhmcsParamsByHostingId($serviceId);
        $this->productConfig       = new ProductConfiguration($serviceId);
        $this->productCustomFields = new ProductCustomFields($this->params['pid'], $this->params['serviceid']);
        $this->tenant              = Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
        $this->vm                  = $vm;
    }

    public function deletePrivateFlavor()
    {
        if ($this->params['customfields']['privateFlavor'])
        {
            try
            {
                $this->tenant->flavor($this->params['customfields']['privateFlavor'])->delete();
            }
            catch (Exception $e)
            {
                if ($e->getCode() != 1404)
                {
                    throw $e;
                }
            }
            $this->productCustomFields->updateFieldValue('privateFlavor', '');
        }
    }

    public function deleteBackups()
    {
        $backupsManager = BackupFactory::getBackupManager($this->productConfig, $this->vm->getUUID());
        $backupsManager->deleteAllBackups();
    }

    public function deleteNetworking()
    {
        Queue::push(DeleteNetworking::class, [
                'hid'  => $this->params['serviceid'],
                'pid'  => $this->params['pid'],
                'vmID' => $this->vm->getUUID()
            ],
            'default',
            null,
            'Hosting',
            $this->params['serviceid']);
    }

    /**
     * @throws Exception
     * @throws OSException
     * @throws \OpenStack\v3\OSException
     */
    public function deleteInstance()
    {
        Api::getInstance()->compute()->delete($this->vm->getUUID());
    }

    public function deleteUsingBlockDevices()
    {
        if (!empty($this->vm->getBlockDevices()))
        {
            Queue::push(DeleteBlockDevices::class,
                [
                    'devices' => $this->vm->getBlockDevices(),
                    'hid'     => $this->params['serviceid'],
                    'pid'     => $this->params['pid'],
                ],
                'default',
                null,
                'Hosting',
                $this->params['serviceid']);
        }

    }

    public function deleteVmID()
    {
        $this->vm->setUUID('');
    }

    public function addDeleteVolumesTask()
    {
        $volumeToDelete = [];
        foreach ($this->vm->getBlockDevices() as $key => $value)
        {
            $volumeToDelete[] = $key;
        }

        if (!empty($volumeToDelete))
        {
            Queue::push(DeleteVolumes::class,
                [
                    'hid'     => $this->params['serviceid'],
                    'pid'     => $this->params['pid'],
                    'volumes' => $volumeToDelete
                ],
                'default',
                null,
                'Hosting',
                $this->params['serviceid']);
        }
    }

    /**
     * Delete key pair from database and OpenStack panel
     */
    public function deleteKeyPair()
    {
        $key = (new Keypairs())->byHostingId($this->params['serviceid'])->first();

        if (\decrypt($key->publicKey))
        {
            $this->deleteKeyFromPanel($key->name);
            $key->delete();
            $this->productCustomFields->updateFieldValue('sshKey', '');
        }
    }

    /**
     * @param string $keyName
     */
    public function deleteKeyFromPanel(string $keyName)
    {
        //Key might got deleted during rebuild
        try {
            $this->tenant->keyPair($keyName)->delete();
        }
        catch (\Throwable) { }
    }

    /**
     * Delete security group if exist
     */
    public function deleteSecurityGroups()
    {
        $groupID = $this->productCustomFields->getCustomFieldsValue(FirewallManager::FIREWALL_SECURITY_GROUP_ID);
        if (!$groupID)
        {
            return;
        }

        $securityGroup = new FirewallManager($this->params);
        $securityGroup->deleteSecurityGroup($groupID);
    }

    /**
     * Delete Settings by service ID from OpenStackVpsCloud_Settings table
     */
    public function deleteServiceSettingsFromDB()
    {
        (new Settings)->deleteSettingsByServiceID($this->params['serviceid']);
    }

    /**
     * @return VPSModel
     */
    public function getVm()
    {
        return $this->vm;
    }


}