<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Repositories\BackupsRepository;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages\BackupsTable;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class BackupsProtectionStatusProvider extends CrudProvider implements ClientAreaInterface, AdminAreaInterface
{
    use TranslatorTrait;

    /**
     * @var VPSModel
     */
    protected $vm;

    public const ACTION_MASS_UPDATE = 'massUpdate';

    public function read()
    {
        parent::read();
    }

    public function update()
    {
        (new BackupsRepository)->changeProtectionStatus([$this->formData['backup_id']]);

        return (new Response())->setSuccess($this->translate('changedProtectionStatus'))
            ->setActions([(new ModalClose()), (new ReloadById(BackupsTable::ID))]);
    }

    public function massUpdate()
    {
        $backupIDs = explode(',', $this->formData['id']);

        (new BackupsRepository)->changeProtectionStatus($backupIDs);

        return (new Response())
            ->setSuccess($this->translate('massChangedProtectionStatus'))
            ->setActions([
                (new ModalClose()),
                (new ReloadById(BackupsTable::ID))
            ]);
    }
}