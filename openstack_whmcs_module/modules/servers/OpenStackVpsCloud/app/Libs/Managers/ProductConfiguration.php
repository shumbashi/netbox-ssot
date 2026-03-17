<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;

class ProductConfiguration
{
    protected $tenantID;

    protected $app;

    protected $region;
    protected $flavor;
    protected $debug_mode;
    protected $fixed_network;
    protected $floating_network;
    protected $ips;
    protected $single_interface;
    protected $backupsFilesLimit;
    protected $minimalTimeBetweenBackups;
    protected $security_groups;
    protected $console_type;
    protected $volume_size;
    protected $rescue_image_ref;
    protected $inboundRulesLimit;
    protected $outboundRulesLimit;
    protected $totalRulesLimit;
    protected $caf_rebuild;
    protected $caf_softreboot;
    protected $caf_protect_vm;
    protected $metadata;
    protected $backupRouting;
    protected $snapshotsFilesLimit;
    protected $scheduledBackups;
    protected $clientScheduledBackpus;
    protected $caf_keypair;
    protected $delete_keypair;
    protected $use_volumes;
    protected $volume_type;
    protected $caf_change_password;
    protected $protect_vm_create;
    protected $caf_additional_rules;
    protected $caf_backups;
    protected $caf_resume;
    protected $caf_console;
    protected $caf_hardreboot;
    protected $caf_scheduled_logs;
    protected $caf_firewall;
    protected $caf_snapshots;
    protected $caf_rescue;
    protected $client_rows;
    protected $admin_rows;
    protected $randomDomainPrefix;
    protected $clearVmDetails;
    protected $newConsoleWindow;
    protected $availability_zone;
    protected $flavorSpecification;
    protected $aaf_stop;
    protected $aaf_pause;
    protected $aaf_softreboot;
    protected $aaf_hardreboot;
    protected $aaf_console;
    protected $aaf_rescue;
    protected $aaf_protect_vm;
    protected $aaf_interfaces;
    protected $aaf_volumes;
    protected $aaf_rebuild;
    protected $aaf_firewall;
    protected $aaf_backups;
    protected $aaf_scheduled_logs;

    protected $vcpus = null;
    protected $ram = null;
    protected $disk = null;

    protected $customFields;
    protected $params;

    //TODO: Change this to ProductConfiguration

    public function __construct(int $hostingId)
    {
        $params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hostingId);

        $productSettings = (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration($params['pid']))->get();

        $configOptions   = $params['configoptions'];

        $this->fill($productSettings);

        $this->flavor              = isset($configOptions['flavor']) ? $configOptions['flavor'] : $this->flavor;
        $this->app                 = isset($configOptions['app']) ? $configOptions['app'] : $this->app;
        $this->ips                 = isset($configOptions['ips']) ? $configOptions['ips'] : $this->ips;
        $this->backupsFilesLimit   = isset($configOptions['backupsFilesLimit']) ? $configOptions['backupsFilesLimit'] : $this->backupsFilesLimit;
        $this->snapshotsFilesLimit = isset($configOptions['snapshotsFilesLimit']) ? $configOptions['snapshotsFilesLimit'] : $this->snapshotsFilesLimit;
        $this->totalRulesLimit     = isset($configOptions['totalRulesLimit']) ? $configOptions['totalRulesLimit'] : $this->totalRulesLimit;
        $this->inboundRulesLimit   = isset($configOptions['inboundRulesLimit']) ? $configOptions['inboundRulesLimit'] : $this->inboundRulesLimit;
        $this->outboundRulesLimit  = isset($configOptions['outboundRulesLimit']) ? $configOptions['outboundRulesLimit'] : $this->outboundRulesLimit;
        $this->region              = isset($configOptions['region']) ? $configOptions['region'] : $this->region;
        $this->volume_type         = isset($configOptions['volume_type']) ? $configOptions['volume_type'] : $this->volume_type;
        $this->availability_zone   = isset($configOptions['availabilityZone']) ? $configOptions['availabilityZone'] : $this->availability_zone;
        $this->single_interface   = isset($configOptions['single_interface']) ? $configOptions['single_interface'] : $this->single_interface;$this->single_interface   = isset($configOptions['single_interface']) ? $configOptions['single_interface'] : $this->single_interface;

        $this->vcpus = isset($configOptions['vcpus']) ? $configOptions['vcpus'] : null;
        $this->ram   = isset($configOptions['ram']) ? $configOptions['ram'] : null;
        $this->disk  = isset($configOptions['disk']) ? $configOptions['disk'] : null;

        $this->customFields = $params['customfields'];
        $this->params       = $params;

        $this->volume_size = isset($configOptions['volume_size']) ? $configOptions['volume_size'] : $this->volume_size;
    }

    protected function fill($productSettings)
    {
        foreach ($productSettings as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTenantID()
    {
        return Servers::byServerID($this->params['serverid'])
            ->byService(Servers::TENANT_ID)
            ->first()->endpoint;
    }

    /**
     * @param mixed $tenantID
     */
    public function setTenantID($tenantID)
    {
        $this->tenantID = $tenantID;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getFlavor()
    {
        return $this->flavor;
    }

    /**
     * @param mixed $flavor
     */
    public function setFlavor($flavor)
    {
        $this->flavor = $flavor;
    }

    /**
     * @return mixed
     */
    public function getFlavorSpecification()
    {
        return $this->flavorSpecification;
    }

    /**
     * @param mixed $flavorSpecification
     */
    public function setFlavorSpecification($flavorSpecification)
    {
        $this->flavorSpecification = $flavorSpecification;
    }

    /**
     * @return bool
     */
    public function getDebugMode()
    {
        return (bool)$this->debug_mode;
    }

    /**
     * @param mixed $debug_mode
     */
    public function setDebugMode($debug_mode)
    {
        $this->debug_mode = (bool)$debug_mode;
    }

    /**
     * @return mixed
     */
    public function getFixedNetwork()
    {
        return $this->fixed_network;
    }

    /**
     * @param mixed $fixed_network
     */
    public function setFixedNetwork($fixed_network)
    {
        $this->fixed_network = $fixed_network;
    }

    /**
     * @return mixed
     */
    public function getFloatingNetwork()
    {
        return $this->floating_network;
    }

    /**
     * @param mixed $floating_network
     */
    public function setFloatingNetwork($floating_network)
    {
        $this->floating_network = $floating_network;
    }

    /**
     * @return mixed
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * @param mixed $ips
     */
    public function setIps($ips)
    {
        $this->ips = $ips;
    }

    /**
     * @return mixed
     */
    public function getBackupsFilesLimit()
    {
        return $this->backupsFilesLimit;
    }

    /**
     * @param mixed $backupsFilesLimit
     */
    public function setBackupsFilesLimit($backupsFilesLimit)
    {
        $this->backupsFilesLimit = $backupsFilesLimit;
    }

    /**
     * @return mixed
     */
    public function getMinimalTimeBetweenBackups()
    {
        return $this->minimalTimeBetweenBackups;
    }

    /**
     * @param mixed $minimalTimeBetweenBackups
     */
    public function setMinimalTimeBetweenBackups($minimalTimeBetweenBackups)
    {
        $this->minimalTimeBetweenBackups = $minimalTimeBetweenBackups;
    }

    /**
     * @return mixed
     */
    public function getSecurityGroups()
    {
        return $this->security_groups;
//        return json_decode($this->security_groups);
    }

    /**
     * @param mixed $security_groups
     */
    public function setSecurityGroups($security_groups)
    {
        $this->security_groups = $security_groups;
    }

    /**
     * @return mixed
     */
    public function getConsoleType()
    {
        return $this->console_type;
    }

    /**
     * @param mixed $console_type
     */
    public function setConsoleType($console_type)
    {
        $this->console_type = $console_type;
    }

    /**
     * @return mixed
     */
    public function getVolumeSize()
    {
        return $this->volume_size;
    }

    /**
     * @param mixed $volume_size
     */
    public function setVolumeSize($volume_size)
    {
        $this->volume_size = $volume_size;
    }

    /**
     * @return mixed
     */
    public function getRescueImageRef()
    {
        return $this->rescue_image_ref;
    }

    /**
     * @param mixed $rescue_image_ref
     */
    public function setRescueImageRef($rescue_image_ref)
    {
        $this->rescue_image_ref = $rescue_image_ref;
    }

    /**
     * @return mixed
     */
    public function getInboundRulesLimit()
    {
        return $this->inboundRulesLimit;
    }

    /**
     * @param mixed $inboundRulesLimit
     */
    public function setInboundRulesLimit($inboundRulesLimit)
    {
        $this->inboundRulesLimit = $inboundRulesLimit;
    }

    /**
     * @return mixed
     */
    public function getOutboundRulesLimit()
    {
        return $this->outboundRulesLimit;
    }

    /**
     * @param mixed $outboundRulesLimit
     */
    public function setOutboundRulesLimit($outboundRulesLimit)
    {
        $this->outboundRulesLimit = $outboundRulesLimit;
    }

    /**
     * @return mixed
     */
    public function getTotalRulesLimit()
    {
        return $this->totalRulesLimit;
    }

    /**
     * @param mixed $totalRulesLimit
     */
    public function setTotalRulesLimit($totalRulesLimit)
    {
        $this->totalRulesLimit = $totalRulesLimit;
    }

    /**
     * @return mixed
     */
    public function getCafRebuild()
    {
        return $this->caf_rebuild;
    }

    /**
     * @param mixed $caf_rebuild
     */
    public function setCafRebuild($caf_rebuild)
    {
        $this->caf_rebuild = $caf_rebuild;
    }

    /**
     * @return mixed
     */
    public function getCafSoftreboot()
    {
        return $this->caf_softreboot;
    }

    /**
     * @param mixed $caf_softreboot
     */
    public function setCafSoftreboot($caf_softreboot)
    {
        $this->caf_softreboot = $caf_softreboot;
    }

    /**
     * @return mixed
     */
    public function getCafProtectVm()
    {
        return $this->caf_protect_vm;
    }

    /**
     * @param mixed $caf_protect_vm
     */
    public function setCafProtectVm($caf_protect_vm)
    {
        $this->caf_protect_vm = $caf_protect_vm;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return mixed
     */
    public function getBackupRouting()
    {
        return $this->backupRouting;
    }

    /**
     * @param mixed $backupRouting
     */
    public function setBackupRouting($backupRouting)
    {
        $this->backupRouting = $backupRouting;
    }

    /**
     * @return mixed
     */
    public function getSnapshotsFilesLimit()
    {
        return $this->snapshotsFilesLimit;
    }

    /**
     * @param mixed $snapshotsFilesLimit
     */
    public function setSnapshotsFilesLimit($snapshotsFilesLimit)
    {
        $this->snapshotsFilesLimit = $snapshotsFilesLimit;
    }

    /**
     * @return mixed
     */
    public function getScheduledBackups()
    {
        return $this->scheduledBackups;
    }

    /**
     * @param mixed $scheduledBackups
     */
    public function setScheduledBackups($scheduledBackups)
    {
        $this->scheduledBackups = $scheduledBackups;
    }

    /**
     * @return mixed
     */
    public function getClientScheduledBackups()
    {
        return $this->clientScheduledBackpus;
    }

    /**
     * @param mixed $clientScheduledBackpus
     */
    public function setClientScheduledBackups($clientScheduledBackpus)
    {
        $this->clientScheduledBackpus = $clientScheduledBackpus;
    }

    /**
     * @return mixed
     */
    public function getCafKeypair()
    {
        return $this->caf_keypair;
    }

    /**
     * @param mixed $caf_keypair
     */
    public function setCafKeypair($caf_keypair)
    {
        $this->caf_keypair = $caf_keypair;
    }

    /**
     * @return mixed
     */
    public function getDeleteKeypair()
    {
        return $this->delete_keypair;
    }

    /**
     * @param mixed $delete_keypair
     */
    public function setDeleteKeypair($delete_keypair)
    {
        $this->delete_keypair = $delete_keypair;
    }

    /**
     * @return mixed
     */
    public function getUseVolumes()
    {
        return $this->use_volumes;
    }

    /**
     * @param mixed $use_volumes
     */
    public function setUseVolumes($use_volumes)
    {
        $this->use_volumes = $use_volumes;
    }

    /**
     * @return mixed
     */
    public function getVolumeType()
    {
        return $this->volume_type;
    }

    /**
     * @param mixed $volume_type
     */
    public function setVolumeType($volume_type)
    {
        $this->volume_type = $volume_type;
    }

    /**
     * @return mixed
     */
    public function getCafChangePassword()
    {
        return $this->caf_change_password;
    }

    /**
     * @param mixed $caf_change_password
     */
    public function setCafChangePassword($caf_change_password)
    {
        $this->caf_change_password = $caf_change_password;
    }

    /**
     * @return mixed
     */
    public function getProtectVmCreate()
    {
        return $this->protect_vm_create;
    }

    /**
     * @param mixed $protect_vm_create
     */
    public function setProtectVmCreate($protect_vm_create)
    {
        $this->protect_vm_create = $protect_vm_create;
    }

    /**
     * @return mixed
     */
    public function getCafAdditionalRules()
    {
        return $this->caf_additional_rules;
    }

    /**
     * @param mixed $caf_additional_rules
     */
    public function setCafAdditionalRules($caf_additional_rules)
    {
        $this->caf_additional_rules = $caf_additional_rules;
    }

    /**
     * @return mixed
     */
    public function getCafBackups()
    {
        return $this->caf_backups;
    }

    /**
     * @param mixed $caf_backups
     */
    public function setCafBackups($caf_backups)
    {
        $this->caf_backups = $caf_backups;
    }

    /**
     * @return mixed
     */
    public function getCafResume()
    {
        return $this->caf_resume;
    }

    /**
     * @param mixed $caf_resume
     */
    public function setCafResume($caf_resume)
    {
        $this->caf_resume = $caf_resume;
    }

    /**
     * @return mixed
     */
    public function getCafConsole()
    {
        return $this->caf_console;
    }

    /**
     * @param mixed $caf_console
     */
    public function setCafConsole($caf_console)
    {
        $this->caf_console = $caf_console;
    }

    /**
     * @return mixed
     */
    public function getCafHardreboot()
    {
        return $this->caf_hardreboot;
    }

    /**
     * @param mixed $caf_hardreboot
     */
    public function setCafHardreboot($caf_hardreboot)
    {
        $this->caf_hardreboot = $caf_hardreboot;
    }

    /**
     * @return mixed
     */
    public function getCafScheduledLogs()
    {
        return $this->caf_scheduled_logs;
    }

    /**
     * @param mixed $caf_scheduled_logs
     */
    public function setCafScheduledLogs($caf_scheduled_logs)
    {
        $this->caf_scheduled_logs = $caf_scheduled_logs;
    }

    /**
     * @return mixed
     */
    public function getCafFirewall()
    {
        return $this->caf_firewall;
    }

    /**
     * @param mixed $caf_firewall
     */
    public function setCafFirewall($caf_firewall)
    {
        $this->caf_firewall = $caf_firewall;
    }

    /**
     * @return mixed
     */
    public function getCafSnapshots()
    {
        return $this->caf_snapshots;
    }

    /**
     * @param mixed $caf_snapshots
     */
    public function setCafSnapshots($caf_snapshots)
    {
        $this->caf_snapshots = $caf_snapshots;
    }

    /**
     * @return mixed
     */
    public function getCafRescue()
    {
        return $this->caf_rescue;
    }

    /**
     * @param mixed $caf_rescue
     */
    public function setCafRescue($caf_rescue)
    {
        $this->caf_rescue = $caf_rescue;
    }

    /**
     * @return mixed
     */
    public function getClientRows()
    {
        return $this->client_rows;
    }

    /**
     * @param mixed $client_rows
     */
    public function setClientRows($client_rows)
    {
        $this->client_rows = $client_rows;
    }

    /**
     * @return mixed
     */
    public function getAdminRows()
    {
        return $this->admin_rows;
    }

    /**
     * @param mixed $admin_rows
     */
    public function setAdminRows($admin_rows)
    {
        $this->admin_rows = $admin_rows;
    }

    /**
     * @return mixed|null
     */
    public function getVcpus()
    {
        return $this->vcpus;
    }

    /**
     * @param mixed|null $vcpus
     */
    public function setVcpus(mixed $vcpus)
    {
        $this->vcpus = $vcpus;
    }

    /**
     * @return mixed|null
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * @param mixed|null $ram
     */
    public function setRam(mixed $ram)
    {
        $this->ram = $ram;
    }

    /**
     * @return mixed|null
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param mixed|null $disk
     */
    public function setDisk(mixed $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @return string|null
     */
    public function getRandomDomainPrefix()
    {
        return $this->randomDomainPrefix;
    }

    /**
     * @param string $randomDomainPrefix
     */
    public function setRandomDomainPrefix(string $randomDomainPrefix = null)
    {
        $this->randomDomainPrefix = $randomDomainPrefix;
    }

    /**
     * @return mixed
     */
    public function getClearVmDetails()
    {
        return $this->clearVmDetails;
    }

    /**
     * @param mixed $clearVmDetails
     */
    public function setClearVmDetails($clearVmDetails)
    {
        $this->clearVmDetails = $clearVmDetails;
    }

    /**
     * @return mixed
     */
    public function getNewConsoleWindow()
    {
        return $this->newConsoleWindow;
    }

    /**
     * @param mixed $newConsoleWindow
     */
    public function setNewConsoleWindow($newConsoleWindow)
    {
        $this->newConsoleWindow = $newConsoleWindow;
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
     * @return array
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getAafStop()
    {
        return $this->aaf_stop;
    }

    /**
     * @param mixed $aaf_stop
     */
    public function setAafStop($aaf_stop)
    {
        $this->aaf_stop = $aaf_stop;
    }

    /**
     * @return mixed
     */
    public function getAafPause()
    {
        return $this->aaf_pause;
    }

    /**
     * @param mixed $aaf_pause
     */
    public function setAafPause($aaf_pause)
    {
        $this->aaf_pause = $aaf_pause;
    }

    /**
     * @return mixed
     */
    public function getAafScheduledLogs()
    {
        return $this->aaf_scheduled_logs;
    }

    /**
     * @param mixed $aaf_scheduled_logs
     */
    public function setAafScheduledLogs($aaf_scheduled_logs)
    {
        $this->aaf_scheduled_logs = $aaf_scheduled_logs;
    }

    /**
     * @return mixed
     */
    public function getAafSoftreboot()
    {
        return $this->aaf_softreboot;
    }

    /**
     * @param mixed $aaf_softreboot
     */
    public function setAafSoftreboot($aaf_softreboot)
    {
        $this->aaf_softreboot = $aaf_softreboot;
    }

    /**
     * @return mixed
     */
    public function getAafHardreboot()
    {
        return $this->aaf_hardreboot;
    }

    /**
     * @param mixed $aaf_hardreboot
     */
    public function setAafHardreboot($aaf_hardreboot)
    {
        $this->aaf_hardreboot = $aaf_hardreboot;
    }

    /**
     * @return mixed
     */
    public function getAafRescue()
    {
        return $this->aaf_rescue;
    }

    /**
     * @param mixed $aaf_rescue
     */
    public function setAafRescue($aaf_rescue)
    {
        $this->aaf_rescue = $aaf_rescue;
    }

    /**
     * @return mixed
     */
    public function getAafProtectVm()
    {
        return $this->aaf_protect_vm;
    }

    /**
     * @param mixed $aaf_protect_vm
     */
    public function setAafProtectVm($aaf_protect_vm)
    {
        $this->aaf_protect_vm = $aaf_protect_vm;
    }

    /**
     * @return mixed
     */
    public function getAafInterfaces()
    {
        return $this->aaf_interfaces;
    }

    /**
     * @param mixed $aaf_interfaces
     */
    public function setAafInterfaces($aaf_interfaces)
    {
        $this->aaf_interfaces = $aaf_interfaces;
    }

    /**
     * @return mixed
     */
    public function getAafVolumes()
    {
        return $this->aaf_volumes;
    }

    /**
     * @param mixed $aaf_volumes
     */
    public function setAafVolumes($aaf_volumes)
    {
        $this->aaf_volumes = $aaf_volumes;
    }

    /**
     * @return mixed
     */
    public function getAafConsole()
    {
        return $this->aaf_console;
    }

    /**
     * @param mixed $aaf_console
     */
    public function setAafConsole($aaf_console)
    {
        $this->aaf_console = $aaf_console;
    }

    /**
     * @return mixed
     */
    public function getAafRebuild()
    {
        return $this->aaf_rebuild;
    }

    /**
     * @param mixed $aaf_rebuild
     */
    public function setAafRebuild($aaf_rebuild)
    {
        $this->aaf_rebuild = $aaf_rebuild;
    }

    /**
     * @return mixed
     */
    public function getAafFirewall()
    {
        return $this->aaf_firewall;
    }

    /**
     * @param mixed $aaf_firewall
     */
    public function setAafFirewall($aaf_firewall)
    {
        $this->aaf_firewall = $aaf_firewall;
    }

    /**
     * @return mixed
     */
    public function getAafBackups()
    {
        return $this->aaf_backups;
    }

    /**
     * @param mixed $aaf_backups
     */
    public function setAafBackups($aaf_backups)
    {
        $this->aaf_backups = $aaf_backups;
    }

    /**
     * @param mixed $app
     */
    public function setApp($app): void
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getSingleInterface()
    {
//        return $this->single_interface;
        return true;
    }

    public function setSingleInterface(mixed $single_interface): void
    {
        $this->single_interface = $single_interface;
    }

}
