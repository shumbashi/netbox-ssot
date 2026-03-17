<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators\ActionValidatorTranslator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ConsoleManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ProtectionVmManager;
use ModulesGarden\OpenStackVpsCloud\App\Models\Keypairs;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms\KeyDownloadForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\DownloadFileFromForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use Illuminate\Database\Capsule\Manager as DB;

class ServiceActionsProvider extends CrudProvider
{
    use TranslatorTrait;
    use ApiTrait;

    const STOP = 'stop';
    const START = 'start';
    const PAUSE = 'pause';
    const UNPAUSE = 'unpause';
    const SOFT_REBOOT = 'softReboot';
    const HARD_REBOOT = 'hardReboot';
    const RESUME = 'resume';
    const CHANGE_PROTECTION = 'changeProtection';
    const DOWNLOAD_PUBLIC_KEY = 'downloadPublicKey';
    const DOWNLOAD_PRIVATE_KEY = 'downloadPrivateKey';
    const CHANGE_PASSWORD = 'changePassword';
    const HARD = 'hard';
    const SOFT = 'soft';
    const CONSOLE = 'console';

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    public function __construct()
    {
        parent::__construct();
        $this->productConfig = new ProductConfiguration(Params::get('serviceid'));
    }

    public function read()
    {
        $instance = $this->api->compute()->getVPSDetails(Params::get('customfields.vmID'));
        parent::read();
        $this->data['status'] = $instance['status'];
    }

    public function start()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->startVPS(Params::get('customfields.vmID'));

            Logger::info(LoggerMessages::INSTANCE_START_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

            return (new Response())
                ->setSuccess($this->translate('VmStartedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_START_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_START_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function stop()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->stopVPS(Params::get('customfields.vmID'));

            Logger::info(LoggerMessages::INSTANCE_STOP_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

            return (new Response())
                ->setSuccess($this->translate('VmStoppedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_STOP_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_STOP_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function pause()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->pauseVPS(Params::get('customfields.vmID'));

            Logger::info(LoggerMessages::INSTANCE_PAUSE_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

            return (new Response())
                ->setSuccess($this->translate('VmPausedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_PAUSE_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_PAUSE_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function unpause()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->unpauseVPS(Params::get('customfields.vmID'));

            Logger::info(LoggerMessages::INSTANCE_UNPAUSE_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);
            return (new Response())
                ->setSuccess($this->translate('VmUnpausedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_UNPAUSE_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_UNPAUSE_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function hardReboot()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->rebootVPS(Params::get('customfields.vmID'), self::HARD);

            Logger::info(LoggerMessages::INSTANCE_HARD_REBOOT_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

            return (new Response())
                ->setSuccess($this->translate('VmHardRebootedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_HARD_REBOOT_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_HARD_REBOOT_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function softReboot()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->rebootVPS(Params::get('customfields.vmID'), self::SOFT);

            Logger::info(LoggerMessages::INSTANCE_SOFT_REBOOT_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

            return (new Response())
                ->setSuccess($this->translate('VmSoftRebootedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_SOFT_REBOOT_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_SOFT_REBOOT_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function resume()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $this->api->compute()->resumeVPS(Params::get('customfields.vmID'));

            Logger::info(LoggerMessages::INSTANCE_RESUME_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

            return (new Response())
                ->setSuccess($this->translate('VmResumeSuccessfully'))
                ->setActions([
                    (new ModalClose()),
            ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_RESUME_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());

        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_RESUME_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $this->formData->toArray()
            ]);

            return (new Response())->setError($e->getMessage());
        }
    }

    public function changeProtection()
    {
        try {
            $protectionManager = new ProtectionVmManager(Params::get('serviceid'));
            $protectionManager->change();

            return (new Response())
                ->setSuccess($this->translate('changedProtectionSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);
        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }
    }

    public function downloadPublicKey()
    {
        try {
            $key = (new Keypairs())->byHostingId(Params::get('serviceid'))->first();
            $publicKey = decrypt($key->publicKey);

            if (!$publicKey) {
                return (new Response())->setError($this->translate('keyNotFound'));
            }

            return (new Response())->setSuccess($this->translate("publicKeyDownloadedSuccessfully"))
                ->setActions([
                    (new ModalClose()),
                    (new DownloadFileFromForm(new KeyDownloadForm(), ['type' => Keypairs::KEY_PUBLIC])),
                ]);

        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }
    }

    public function downloadPrivateKey()
    {
        try {
            $key = (new Keypairs())->byHostingId(Params::get('serviceid'))->first();
            $privateKey = decrypt($key->key);

            if (!$privateKey) {
                return (new Response())->setError($this->translate('keyNotFound'));
            }

            return (new Response())->setSuccess($this->translate("privateKeyDownloadedSuccessfully"))
                ->setActions([
                    (new ModalClose()),
                    (new DownloadFileFromForm(new KeyDownloadForm(), ['type' => Keypairs::KEY_PRIVATE]))
                ]);

        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }
    }

    public function changePassword()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $vmId = Params::get('customfields.vmID');
            $this->api->compute()->changePassword($vmId, $this->formData['password']);

            DB::statement("UPDATE tblhosting SET password = ? WHERE id = ?", [\encrypt($this->formData['password']), Params::get('serviceid')]);

            return (new Response())
                ->setSuccess($this->translate('passwordChangedSuccessfully'))
                ->setActions([
                    (new ModalClose()),
                ]);

        } catch (\Exception $exc) {
            return (new Response())->setError($exc->getMessage());
        }
    }

    public function console()
    {
        $consoleUrl = (new ConsoleManager(Params::get('customfields.vmID')))
            ->getConsoleUrl();

        $redirect = new Redirect($consoleUrl);
        if ($this->productConfig->getNewConsoleWindow()) {
            $redirect->openNewWindow();
        }

        return (new Response())
            ->setActions([$redirect]);
    }

    public function update()
    {
        //do nothing
    }

    protected function getVm()
    {
        $tenant = Factory::getTenantFromServiceId(Params::get('serviceid'), false);
        $vmID = Params::get('customfields')['vmID'];

        return $tenant->VPS($vmID);
    }

    protected function getProductConfig()
    {
        return new ProductConfiguration(Params::get('serviceid'));
    }
}
