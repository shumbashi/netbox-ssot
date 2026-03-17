<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\KeyDownloadProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class KeyDownloadForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $provider = KeyDownloadProvider::class;
    protected ?string $providerDefaultAction = null;

    public function loadHtml(): void
    {
        $this->builder->addElement((new HiddenField())->setName("type"));
    }
}