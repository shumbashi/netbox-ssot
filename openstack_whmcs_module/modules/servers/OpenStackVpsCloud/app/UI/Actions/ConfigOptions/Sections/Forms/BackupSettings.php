<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class BackupSettings extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new Number())->setName('customconfigoption[backupsFilesLimit]'), true);
        $this->builder->addField((new Number())->setName('customconfigoption[minimalTimeBetweenBackups]'), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[backupRouting]'), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[scheduledBackups]'), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[clientScheduledBackpus]'), true);
    }
}

