<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class CaFeatures extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_backups]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_resume]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_rebuild]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_console]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_softreboot]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_hardreboot]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_scheduled_logs]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_protect_vm]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_firewall]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_rescue]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_snapshots]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_changepassword]'));
        $this->builder->addField((new Dropdown())->setName('customconfigoption[client_rows]')->setDescription($this->translate('customconfigoption[client_rows].description'))->setMultiple(true), true);
    }
}

