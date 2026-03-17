<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms\CreateItemConfigForm;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;

class CreateItemConfigModal extends ModalEdit implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $typeName = (new AppTypesTranslator())->getSingularTranslatedName(
            Item::where('id', Request::get('id'))->first()->type
        );

        $this->setTitle($this->translate('title', ['name' => $typeName]));
        $this->setSize(self::SIZE_EXTRA_LARGE);
        $this->addElement(new CreateItemConfigForm());
    }
}