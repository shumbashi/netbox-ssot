<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class ProjectSettings extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new FormInputText())->setName('customconfigoption[randomDomainPrefix]')->setDescription($this->translate('customconfigoption[randomDomainPrefix].description')), true);

        $this->builder->addField((new Dropdown())->setName('customconfigoption[region]'));
        $this->builder->addField((new Dropdown())->setName('customconfigoption[availability_zone]')
            ->setDescription($this->translate('customconfigoption[availability_zone].description')), true);

        $this->builder->addField((new Dropdown())->setName('customconfigoption[flavor]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[debug_mode]')->setDescription($this->translate('customconfigoption[debug_mode].description')), true);
    }
}

