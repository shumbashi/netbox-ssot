<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;

class CreateConfigurableOptions extends ModalEdit implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setSize(Config::get(ConfigSettings::CONFIG_OPTIONS_MODAL_SIZE, ""));

        $this->addElement((new AlertInfo())->setText($this->translate('configurableOptionsNameInfo', ['configurableOptionsNameUrl' => 'https://docs.whmcs.com/Addons_and_Configurable_Options'])));
        $this->addElement(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms\CreateConfigurableOptions());
    }
}