<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class SendCustomWelcomeEmail extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new Dropdown())->setName('customconfigoption[emailTemplate]'));
        $this->builder->addField((new Dropdown())->setName('customconfigoption[rebuildEmailTemplate]')->setDescription($this->translate('customconfigoption[rebuildEmailTemplate].description')), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[sendWelcomeEmail]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[sendRebuildEmail]'));
    }
}

