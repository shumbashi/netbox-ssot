<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class FirewallSettings extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->addField((new Number())->setName('customconfigoption[totalRulesLimit]')->setDescription($this->translate('customconfigoption[totalRulesLimit].description')), true);
        $this->builder->addField((new Number())->setName('customconfigoption[inboundRulesLimit]')->setDescription($this->translate('customconfigoption[inboundRulesLimit].description')), true);
        $this->builder->addField((new Number())->setName('customconfigoption[outboundRulesLimit]')->setDescription($this->translate('customconfigoption[outboundRulesLimit].description')), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_additional_rules]')->setDescription($this->translate('customconfigoption[caf_additional_rules].description')), true);
    }
}

