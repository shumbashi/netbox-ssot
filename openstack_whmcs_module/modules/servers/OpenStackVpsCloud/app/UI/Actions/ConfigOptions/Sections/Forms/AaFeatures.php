<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class AaFeatures extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_stop]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_pause]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_softreboot]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_hardreboot]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_interfaces]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_protect_vm]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_volumes]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_console]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_rebuild]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_rescue]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_snapshots]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_scheduled_logs]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_firewall]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_changepassword]'));
        $this->builder->addField((new Switcher())->setName('customconfigoption[aaf_backups]'));

        $this->builder->addField((new Dropdown())->setName('customconfigoption[admin_rows]')->setDescription($this->translate('customconfigoption[admin_rows].description'))->setMultiple(true), true);
    }
}

