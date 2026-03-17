<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSubmitSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
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

class EditItemForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_UPDATE;
    protected string $provider = ItemProvider::class;

    public function loadHtml(): void
    {
        $this->builder->addField((new HiddenField())->setName('id'));
        $this->builder->addField((new FormInputText())->setName('name')->required());
        $this->builder->addField((new TextArea())->setName('description'), true);
        $this->builder->addField((new StatusDropdown())->setName('status'));
        $this->builder->addField((new ImagePicker())
            ->setTitle($this->translate('image'))
            ->setDropdownHeight(ImagePicker::DROPDOWN_HEIGHT_2_ROW)
            ->setName('image')
            ->setMediaLibrary(new MediaLibrarySelectOnly()));

        $this->addElement((new ButtonSubmitSuccess())
            ->setName('button_submit')
            ->setTitle($this->translate('button_submit')));
    }
}