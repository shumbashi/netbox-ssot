<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ServiceActionsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\Alert;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

/**
 * Class DownloadPublicKeyForm
 * @package ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms
 */
class DownloadPublicKeyForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $provider = ServiceActionsProvider::class;
    protected string $providerAction = ServiceActionsProvider::DOWNLOAD_PUBLIC_KEY;

    public function loadHtml(): void
    {
        $alert = (new AlertInfo())
            ->setText($this->translate('downloadKeyConfirmMessage'));

        $this->addElement($alert);

    }
}