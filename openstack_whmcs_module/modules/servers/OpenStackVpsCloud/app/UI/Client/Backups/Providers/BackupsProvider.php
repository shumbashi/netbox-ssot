<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeleteBackups;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Factories\BackupFactory;
use ModulesGarden\OpenStackVpsCloud\App\Models\Backups;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages\BackupsTable;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class BackupsProvider extends CrudProvider implements ClientAreaInterface, AdminAreaInterface
{
    use TranslatorTrait;

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
        parent::read();
    }

    public function create()
    {
        $backupName = $this->formData['backupName'];
        $backupManager = BackupFactory::getBackupManager($this->productConfig, Params::get('customfields.vmID'));

        try {
            $backupManager->createBackup($backupName);
        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }

        return (new Response())->setSuccess($this->translate('BackupCreation'))
            ->setActions([
                (new ModalClose()),
                (new ReloadById(BackupsTable::ID))
            ]);
    }

    public function update()
    {
        $backupID = $this->formData['id'];

        try {
            $backupManager = BackupFactory::getBackupManager($this->productConfig, Params::get('customfields.vmID'));
            $backupManager->restoreBackup($backupID, Params::get('password'));
        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }

        return (new Response())->setSuccess($this->translate('BackupRestoring'))
            ->setActions([
                (new ModalClose()),
                (new ReloadById(BackupsTable::ID))
            ]);
    }

    public function delete()
    {
        $backupIDs = explode(',', $this->formData['id']);
        foreach ($backupIDs as $backupID)
        {
            $backup = Backups::where('backupID', $backupID)->first();
            if (!$backup) {
                return (new Response())->setError($this->translate('backupNotFound', ['id' => $backupID]));
            }

            if ($backup->pinned)
            {
                return (new Response())->setError($this->translate('backupPinned', ['name' => $backup->backupName]));
            }
        }

        Queue::push(DeleteBackups::class,
            [
                'hid' => Params::get('serviceid'),
                'pid' => Params::get('pid'),
                'backupsIDs' => $backupIDs
            ],
            'default',
            null,
            'Hosting',
            Params::get('serviceid'));


        $message = count($backupIDs) > 1 ? 'backupsDeleting' : 'backupDeleting';

        return (new Response())->setSuccess($this->translate($message))->setActions([
            (new ModalClose()),
            (new ReloadById(BackupsTable::ID))
        ]);
    }
}