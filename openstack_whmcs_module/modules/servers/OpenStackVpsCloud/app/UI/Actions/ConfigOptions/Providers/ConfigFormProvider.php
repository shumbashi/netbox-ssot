<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\ConsoleTypes;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\InstanceInfo;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\BaseCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\SecurityGroupModel;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers as CustomServerModel;
use ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\EmailTemplate;

class ConfigFormProvider extends \ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers\ProductConfiguration
{
    use TranslatorTrait;

    const NETWORK_DISABLED = 'Disabled';
    const IMAGE_DEFAULT = 'Default';

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var int
     */
    protected $serverId;


    /**
     * @var Tenant
     */
    protected $tenant;

    public function read()
    {
        parent::read();

        $this->loadProductConfig();
        $this->loadProjectSettingsData();
    }


    private function loadProjectSettingsData()
    {
        $serverModel = new CustomServerModel();

        $images = collect(array_values(Api::getInstance()->image()->listImages()))
            ->pluck('name', 'id')
            ->toArray();

        asort($images);

        $availableImages = array_merge(['default' => self::IMAGE_DEFAULT], $images);

        $this->availableValues['customconfigoption[rescue_image_ref]'] = $availableImages;

        try {
            $types = Api::getInstance()->volume()->getTypes();
            $avaibleVolumeTypes = [];
            foreach ($types as $type) {
                $avaibleVolumeTypes[$type['name']] = $type['name'];
            }
            $this->availableValues['customconfigoption[volume_type]'] = $avaibleVolumeTypes;
        } catch (\Exception $ex) {
//            if(!(preg_match('/Cant find node/i', $ex->getMessage()) && preg_match('/for interface: volume/i', $ex->getMessage())))
//            {
//                throw $ex;
//            }
            //Cant find node: for interface: volume
        }

        $instanceAvailableInfo = [];
        foreach (InstanceInfo::getAll() as $info) {
            $instanceAvailableInfo[$info] = $this->translate($info);
        }

        $this->availableValues['customconfigoption[client_rows]'] = $instanceAvailableInfo;
        $this->availableValues['customconfigoption[admin_rows]'] = $instanceAvailableInfo;

        $regions = $this->tenant->api()->getApIdentity()->getRegions();
        $availableRegions = [];
        foreach ($regions as $key => $region) {
            $availableRegions[$region] = $region;
        }
        $this->availableValues['customconfigoption[region]'] = $availableRegions;

        /**
         * @var BaseCacheModel[] $flavors
         */
        $flavors = $serverModel->getEndpoint($this->serverId, CustomServerModel::AVAILABLE_FLAVORS);

        $availableFlavors = [];
        foreach ($flavors as $flavor) {
            $availableFlavors[$flavor->getUUID()] = $flavor->getName();
        }
        $this->availableValues['customconfigoption[flavor]'] = $availableFlavors;

        $zones = Api::getInstance()->compute()->getAvailableAvailabilityZone();
        $availableZones = [];
        foreach ($zones as $zone) {
            $availableZones[$zone['zoneName']] = $zone['zoneName'];
        }
        $this->availableValues['customconfigoption[availability_zone]'] = ['auto' => $this->translate('availability_zone.autoAvailableZone')] + $availableZones;
        /**
         * @var BaseCacheModel[] $networks
         */
        $networks = $serverModel->getEndpoint($this->serverId, CustomServerModel::AVAILABLE_NETWORKS);

        $availableNetworks = [];
        foreach ($networks as $network) {
            $availableNetworks[$network->getUUID()] = $network->getName();
        }
        $this->availableValues['customconfigoption[fixed_network]'] = $availableNetworks;
        array_unshift($availableNetworks, self::NETWORK_DISABLED);
        $this->availableValues['customconfigoption[floating_network]'] = $availableNetworks;

        /**
         * @var SecurityGroupModel[] $securityGroups
         */
        $securityGroups = $serverModel->getEndpoint($this->serverId, CustomServerModel::AVAILABLE_SECURITY_GROUPS);
        $availableSecurityGroups = [];
        foreach ($securityGroups as $group) {
            $availableSecurityGroups[$group->getUUID()] = $group->getName();
        }
        $this->availableValues['customconfigoption[security_groups]'] = $availableSecurityGroups;

        $availableConsoleTypes = [];
        foreach (ConsoleTypes::CONSOLE_TYPES as $type) {
            $availableConsoleTypes[$type] = $type;
        }
        $this->availableValues['customconfigoption[console_type]'] = $availableConsoleTypes;

        $emailTemplates = $this->getEmailTemplates();
        $availableEmailTemplates = [];
        foreach ($emailTemplates as $template) {
            $availableEmailTemplates[$template->name] = $template->name;
        }
        $this->availableValues['customconfigoption[emailTemplate]'] = $availableEmailTemplates;
        $this->availableValues['customconfigoption[rebuildEmailTemplate]'] = $availableEmailTemplates;
    }

    protected function loadProductConfig()
    {
        $this->productId = Request::get('id');

        $server = ServerRepository::findByGroupId(Request::get('servergroup'));

        $this->serverId = $server->id;

        $this->tenant = Factory::adminFromServerId($server->id);

    }

    public function getEmailTemplates()
    {
        return EmailTemplate::where('type', '=', 'product')->get();
    }
}
