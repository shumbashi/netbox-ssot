<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class RebuildVMModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        //TODO: rebuild vm modal
    }
}