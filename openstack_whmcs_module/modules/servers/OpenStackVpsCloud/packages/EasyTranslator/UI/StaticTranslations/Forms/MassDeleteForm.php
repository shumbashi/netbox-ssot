<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Providers\MassDeleteProvider;

class MassDeleteForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = MassDeleteProvider::class;
    protected string $providerAction = CrudProvider::ACTION_DELETE;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
    }
}