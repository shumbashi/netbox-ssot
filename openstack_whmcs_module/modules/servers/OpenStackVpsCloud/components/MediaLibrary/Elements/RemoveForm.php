<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\Elements;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;

abstract class RemoveForm extends Form implements AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_DELETE;
}
