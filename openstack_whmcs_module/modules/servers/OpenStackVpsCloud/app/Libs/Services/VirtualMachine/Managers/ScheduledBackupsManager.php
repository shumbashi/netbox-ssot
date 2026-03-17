<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\ScheduledBackups;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Models\Settings;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class ScheduledBackupsManager
{
    use TranslatorTrait;

    /**
     * @var array
     */
    protected $whmcsParams;

    protected ?array $productConfig;

    const TIME_BETWEEN_BACKUPS = 'timeBetweenBackups';

    public function __construct(string $vmID, array $params = [])
    {
        $this->whmcsParams = $params;
        if (empty($this->whmcsParams)) {
            $this->whmcsParams = WhmcsParamsHelper::getWhmcsParamsByVmId($vmID);
        }

        $this->productConfig = (new ProductConfiguration($this->whmcsParams['packageid']))->get();
    }

    /**
     * @param int|null $timeInterval
     */
    public function setScheduledBackups(int $timeInterval = null)
    {
        $minimalInterval = $this->productConfig['minimalTimeBetweenBackups'];
        if ($timeInterval < (int)$minimalInterval)
        {
            throw new \Exception($this->translate('interval_too_low', ['interval' => $timeInterval]));
        }

        $timeInterval = $timeInterval ?: $this->productConfig['minimalTimeBetweenBackups'];

        //TODO: move this into custom field
        $settingsModel = new Settings();
        $settingsModel->setSetting($this->whmcsParams['serviceid'], self::TIME_BETWEEN_BACKUPS, $timeInterval);

        if ($this->isActiveScheduledBackupsTask())
        {
            return;
        }

        Queue::push(ScheduledBackups::class,
            [
                'hid'  => $this->whmcsParams['serviceid'],
                'pid'  => $this->whmcsParams['pid']
            ],
            'default',
            null,
            'Hosting',
            $this->whmcsParams['serviceid']);
    }

    /**
     * @return mixed
     */
    public function isActiveScheduledBackupsTask()
    {
        return (new JobManager())->isActiveTask($this->whmcsParams['serviceid'], JobManager::SCHEDULED_BACKUPS);
    }

    /**
     * @return int
     */
    public function getTimeInterval()
    {
        $timeInterval = Settings::byServiceID($this->whmcsParams['serviceid'])
            ->bySetting(self::TIME_BETWEEN_BACKUPS)
            ->first()
            ->value;

        return (int)$timeInterval;
    }
}