<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\PostCreationSetters;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration as ProductConfig;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\FlavorCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\ImageCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\NetworkCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\CacheResources\SecurityGroupsCacheModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\MetadataBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\NetworkInterfacesManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ProtectionVmManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\SecurityGroupManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Smarty\SmartyService;
use ModulesGarden\OpenStackVpsCloud\App\Models\Keypairs;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Services\Mailer;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\di;

use Illuminate\Database\Capsule\Manager as DB;

class BaseSetter
{
    const SEND_EMAIL = 'SendEmail';

    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * @var array
     */
    protected $params;


    //TODO: slowly get rid of this
    /**
     * @var ProductConfig
     */
    protected $productConfig;

    protected ?array $productConfiguration = [];

    /**
     * @var ProductCustomFields
     */
    protected $productCustomFields;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * BaseSetter constructor.
     * @param array $params
     * @param VPSModel $vm
     */
    public function __construct(array $params, VPSModel $vm)
    {
        $this->params              = $params;
        $this->vm                  = $vm;
        $this->productConfig       = new ProductConfig($params['serviceid']);
        $this->productConfiguration = (new ProductConfiguration($params['pid']))->get();
        $this->productCustomFields = new ProductCustomFields($params['pid'], $params['serviceid']);
        $this->tenant              = Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
    }

    /**
     * @throws Exception
     */
    public function updateCustomFieldOfVmId()
    {
        $this->productCustomFields->updateFieldValue('vmID', $this->vm->getUUID());
    }

    /**
     * Update Password in tblhosting
     */
    public function setPassword()
    {
        if (!$this->productConfig->getCafChangePassword()) {
            return;
        }

        $password = $this->vm->getPassword();

        $encrypted = Api::getInstance()->compute()->getPassword($this->vm->getUUID())['password'];
        if (!empty($encrypted)) {
            if (!$this->vm->getSshKey() || empty($this->vm->getSshKey()->getPrivate())) {
                throw new \Exception('No ssh key provided for password decryption');
            }

            $encrypted = base64_decode($encrypted);
            openssl_private_decrypt($encrypted, $password, $this->vm->getSshKey()->getPrivate());
        }

        if (!empty($password)) {
            DB::statement("UPDATE tblhosting SET password = ? WHERE id = ?", [\encrypt($password), $this->params['serviceid']]);
            $this->params['password'] = $password;
        }
    }

//    /**
//     * Update Metadata
//     */
//    public function updateMetadata()
//    {
//        $metadataBuilder = new MetadataBuilder(;
//        $metadata        = $metadataBuilder->build();
//
//        Api::getInstance()->compute()->updateMetadata($this->vm->getUUID(), $metadata);
//    }

    public function setUsernameFromCustomScript()
    {
        preg_match('/username:(.*)/', $this->vm->getCustomScript(), $outputArray);
        $username = '';
        if(!empty($outputArray)) {
            $username = $outputArray[1];
        }

         if(!empty($username)) {
            $username = (new SmartyService())->assign($this->params)->fetch('string:' . $username);
            DB::statement("UPDATE tblhosting SET username = ? WHERE id = ?", [$username, $this->params['serviceid']]);
            $this->params['username'] = $username;
        }
    }

    public function recreateNetwork()
    {
        // do nothing, ips already built
    }

    /**
     * @param bool $forceToLoadInterface
     * @throws \Exception
     */
    public function setIPAddresses()
    {
        $service = Service::find($this->params['serviceid']);

        $interfacesManager = new NetworkInterfacesManager($this->vm->getUUID(), $this->params);
        $ips = $interfacesManager->getServiceIps($service->dedicatedip);

        /*This must use raw query model due to fillables*/
        DB::statement("UPDATE tblhosting SET dedicatedip = ?, assignedips = ? WHERE id = ?", [
            $ips['dedicatedip'],
            $ips['assignedips'],
            $this->params['serviceid']]);
    }

    /**
     * Set security groups
     */
    public function setSecurityGroups()
    {
        $this->params['customfields']['vmID'] = $this->vm->getUUID();

        $securityGroups = $this->productConfig->getSecurityGroups();

        $firewallManager = new FirewallManager($this->params);
        $firewallEnabled = $this->productConfig->getCafFirewall() || $this->productConfig->getAafFirewall();
        if ($firewallEnabled) {
            $firewallGroup = $firewallManager->createSecurityGroup();
            if ($this->productConfig->getCafAdditionalRules() && isset($firewallGroup['id'])) {
                $firewallManager->addAdditionalRules($firewallGroup['id']);
            }

            $securityGroups[] = $firewallGroup['id'];
        }

        $securityGroupManager = (new SecurityGroupManager($this->vm->getUUID(), $this->productConfig->getTenantId()));
        $securityGroupManager->change($securityGroups);
    }

    /**
     * Add key pair to database or update if exist
     */
    public function setSshKeyPair()
    {
        if ($this->vm->getSshKey())
        {
            $key = new Keypairs();
            $key->createOrUpdate($this->params['serviceid'], $this->vm->getSshKey());
        }
    }

    /**
     * Set protect VM if require
     */
    public function setProtectVM()
    {
        if ($this->productConfig->getProtectVmCreate())
        {
            $protectVM = new ProtectionVmManager($this->params['serviceid']);
            $protectVM->setTrue();
        }
    }

    public function sendCreationEmailIfSet()
    {
        if (!$this->productConfiguration['sendWelcomeEmail']) {
            return;
        }

        if (!$this->productConfiguration['emailTemplate']) {
            return;
        }

        (new Mailer())->sendFromTemplate($this->productConfiguration['emailTemplate'], $this->params['serviceid']);
    }

    public function sendRebuildEmailIfSet()
    {
        if (!$this->productConfiguration['sendRebuildEmail']) {
            return;
        }

        if (!$this->productConfiguration['rebuildEmailTemplate']) {
            return;
        }

        (new Mailer())->sendFromTemplate($this->productConfiguration['rebuildEmailTemplate'], $this->params['serviceid']);
    }

    /**
     * @param string $idOrName
     * @param string $service (flavor, network, image)
     * @param string $serviceSetting
     * @param array $listOfResources
     * @param string|null $customFieldFriendlyName
     * @return string|null
     * @throws Exception
     */
    protected function getServiceIdFromSelectedRegionResources(string $idOrName, string $service, string $serviceSetting, array $listOfResources, string $customFieldFriendlyName = null)
    {
        $serviceName = $this->getResourceName($idOrName, $customFieldFriendlyName, $serviceSetting);

        /**
         *  Get service ID by service name from list of available services in selected region.
         *
         *  The module assumes that all regions have identical services names.
         */
        foreach ($listOfResources as $resource)
        {
            if ($resource['name'] == $serviceName)
            {
                return $resource['id'];
            }
        }

        /**
         * Throw exception if selected service is not available in selected region
         */

        throw new \Exception(sprintf('%s %s is not available in region %s.', ucfirst($service), $serviceName, $this->productConfig->getRegion()));
    }

    /**
     * Get resource name from saved cache
     *
     * @param string $searchedValue (id or name of selected resource)
     * @param string|null $customFieldFriendlyName
     * @param string $serviceSettingName
     * @return string|null
     */
    protected function getResourceName(string $searchedValue, ?string $customFieldFriendlyName, string $serviceSettingName)
    {
        /**
         * Resources saved in cache (from only one region)
         */
        $serverModel        = new Servers();
        $availableResources = $serverModel->getEndpoint($this->params['serverid'], $serviceSettingName);

        /**
         * @var $resourceModel FlavorCacheModel|ImageCacheModel|NetworkCacheModel|SecurityGroupsCacheModel
         */
        foreach ($availableResources as $resourceModel)
        {
            $resourceId = $resourceModel->getUUID();

            if ($searchedValue === $resourceId ||
                $searchedValue === $resourceModel->getName() ||
                $customFieldFriendlyName === $resourceId ||
                $customFieldFriendlyName === $resourceModel->getName())
            {
                return $resourceModel->getName();
            }
        }

        return null;
    }
}