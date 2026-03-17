<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals\StopVMModal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class StopVMModalButton extends VpsActionButtonCritical implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        parent::loadHtml();
        $this->setImagePath('stop.png');
        $this->onClick((new ModalLoad(new StopVMModal())));
    }
}