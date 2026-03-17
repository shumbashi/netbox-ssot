<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class SnapshotSettings extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new Number())->setName('customconfigoption[snapshotsFilesLimit]')->setDescription($this->translate('customconfigoption[snapshotsFilesLimit].description')), true);
    }
}

