<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ScheduledBackupsManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms\ScheduledBackupsConfForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class ScheduledBackupsProvider extends CrudProvider implements ClientAreaInterface, AdminAreaInterface
{
    use TranslatorTrait;

    /**
     * @var array
     */
    protected $whmcsParams;

    /**
     * @var ScheduledBackupsManager
     */
    protected $scheduledBackupsManager;

    public function __construct()
    {
        parent::__construct();

        $serviceID = Params::get('serviceid');
        $this->whmcsParams = WhmcsParamsHelper::getWhmcsParamsByHostingId($serviceID);
        $this->scheduledBackupsManager = new ScheduledBackupsManager(Params::get('customfields.vmID'), $this->whmcsParams);
    }

    public function read()
    {
        parent::read();

        $this->data['enableBackup'] = $this->scheduledBackupsManager->isActiveScheduledBackupsTask();
        $this->data['timeInterval'] = $this->scheduledBackupsManager->getTimeInterval();
    }

    public function update()
    {
        try {
            $jobManager = new JobManager();

            if ($this->formData['enableBackup'] !== "1") {
                $jobManager->deleteTask(Params::get('serviceid'), JobManager::SCHEDULED_BACKUPS);

                return (new Response())
                    ->setSuccess($this->translate('scheduledBackupsSetDisable'))
                    ->setActions([
                        (new ModalClose()),
                        (new ReloadById(ScheduledBackupsConfForm::ID))
                    ]);
            }

            try {
                $this->scheduledBackupsManager->setScheduledBackups($this->formData['timeInterval']);
            } catch (Exception $exception) {

                return (new Response())
                    ->setActions([new ReloadById(ScheduledBackupsConfForm::ID)])
                    ->setError($exception->getMessage());
            }

            return (new Response())
                ->setSuccess($this->translate('scheduledBackupsSetSuccessful'))
                ->setActions([
                    (new ModalClose()),
                    (new ReloadById(ScheduledBackupsConfForm::ID))
            ]);

        } catch (\Exception $exception) {
            return (new Response())
                ->setActions([new ReloadById(ScheduledBackupsConfForm::ID)])
                ->setError($exception->getMessage());
        }

    }
}