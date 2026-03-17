<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\ImagePicker\ImagePicker;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppStatusTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\ItemMassProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Pages\MediaLibrarySelectOnly;

class MassEditItemForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ItemMassProvider::class;
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public const NO_CHANGE = 'no_change';

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');

        $this->builder->addField((new ImagePicker())
            ->setTitle($this->translate('image'))
            ->setDropdownHeight(ImagePicker::DROPDOWN_HEIGHT_2_ROW)
            ->setName('image')
            ->setMediaLibrary(new MediaLibrarySelectOnly()));

        $this->builder->addField((new Dropdown())
            ->setOptions($this->getOptions())
            ->setDefaultValueAsFirstOption()
            ->setTitle($this->translate('status'))
            ->setName('status'));
    }

    private function getOptions() : array
    {
        return array_merge(
            [
                self::NO_CHANGE => $this->translate(self::NO_CHANGE)
            ],
            (new AppStatusTranslator())->getAvailableTranslated()
        );
    }
}