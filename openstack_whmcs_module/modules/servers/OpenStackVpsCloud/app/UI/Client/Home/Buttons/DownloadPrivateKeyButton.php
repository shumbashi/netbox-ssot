<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals\DownloadPrivateKeyModal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class DownloadPrivateKeyButton extends VpsActionButton implements ClientAreaInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setImagePath('downloadKeyButton.png');
        $this->onClick((new ModalLoad(new DownloadPrivateKeyModal())));
    }
}