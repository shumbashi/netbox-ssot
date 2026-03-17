<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers\DownloadExportedProvider;

class DownloadExportedForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = DownloadExportedProvider::class;
}