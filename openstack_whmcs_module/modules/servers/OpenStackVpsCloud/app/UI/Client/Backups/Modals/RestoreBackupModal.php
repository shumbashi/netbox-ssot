<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms\RestoreBackupForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

/**
 * Class RestoreBackupModal
 * @package ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals
 */
class RestoreBackupModal extends ModalDanger implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{

    public function loadHtml(): void
    {
        $this->setContent($this->translate("restoreBackupConfirmMess"));

        $this->addElement(new RestoreBackupForm());
    }
}