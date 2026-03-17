<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Modals;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms\DownloadPrivateKeyForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class DownloadPrivateKeyModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $serviceId = Params::get('serviceid');
        $message = $this->translate('downloadKeyConfirmMessage');

        $productConfig = new ProductConfiguration($serviceId);
        if ($productConfig->getDeleteKeypair()) {
            $message .= ' ' . $this->translate('downloadKeyOnlyOnce');
        }

        $this->setContent($message);
        $this->addElement(new DownloadPrivateKeyForm());
    }
}