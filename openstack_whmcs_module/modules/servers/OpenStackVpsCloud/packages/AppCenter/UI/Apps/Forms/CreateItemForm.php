<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\ImagePicker\ImagePicker;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Dropdowns\StatusDropdown;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\ItemProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Pages\MediaLibrarySelectOnly;

class CreateItemForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_CREATE;
    protected string $provider = ItemProvider::class;

    public function loadHtml(): void
    {
        $this->builder->addField((new HiddenField())->setName('type'));

        $this->builder->addField((new FormInputText())
            ->setTitle($this->translate('name'))
            ->setName('name')
            ->required());

        $this->builder->addField((new TextArea())
            ->setTitle($this->translate('description'))
            ->setName('description'));

        $this->builder->addField((new StatusDropdown())
            ->setTitle($this->translate('status'))
            ->setName('status'));

        $this->builder->addField((new ImagePicker())
            ->setTitle($this->translate('image'))
            ->setDropdownHeight(ImagePicker::DROPDOWN_HEIGHT_2_ROW)
            ->setName('image')
            ->setMediaLibrary(new MediaLibrarySelectOnly()));
    }
}