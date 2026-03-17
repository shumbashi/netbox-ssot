<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Modals;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms\MassDeleteBackupsForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class MassDeleteBackupsModal extends ModalDanger implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setContent($this->translate('deleteBackupsConfirmMess'));
        $this->addElement(new MassDeleteBackupsForm());
    }

}