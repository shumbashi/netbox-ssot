<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Components\Container\ContainerColumn;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroupFullWidth;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroupHalfWidth;
use ModulesGarden\OpenStackVpsCloud\Components\RowFluid\RowFluid;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\AbstractConfigurableOption;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptionsGroups\ConfigurableOptionsGroup;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Builders\ConfigurableOptionsBuilder;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Widgets\ConfigOptionsGroupWidget;

class CreateConfigurableOptions extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected const COS_COUNT_FOR_TWO_COLUMNS = 10;

    protected string $provider = \ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers\CreateConfigurableOptions::class;

    public function __construct()
    {
        parent::__construct();

        $this->builder = (new ConfigurableOptionsBuilder($this));
    }

    public function loadHtml(): void
    {
        $configurableOptions = is_callable(Config::get(ConfigSettings::CONFIG_OPTIONS_LOADER)) ?
            Config::get(ConfigSettings::CONFIG_OPTIONS_LOADER)(Request::get('id')) :
            Config::get(ConfigSettings::CONFIG_OPTIONS);

        $this->configureBuilder($configurableOptions);

        foreach ($configurableOptions as $configOption)
        {
            $configOption instanceof ConfigurableOptionsGroup ? $this->buildGroup($configOption) : $this->buildSingle($configOption);
        }
    }

    protected function configureBuilder(array $configurableOptions): void
    {
        $this->containsGroup($configurableOptions) ?
            $this->configureBuilderForGroups(count($configurableOptions) > 1) :
            $this->configureBuilderForSingleCOs(count($configurableOptions) > self::COS_COUNT_FOR_TWO_COLUMNS);
    }

    protected function configureBuilderForGroups(bool $twoColumns = false): void
    {
        $this->builder = (new ConfigurableOptionsBuilder($this));

        $twoColumns ?
            $this->builder
                ->setDefaultFormGroup(new FormGroupHalfWidth())
                ->addDefaultContainer(new RowFluid()) :
            $this->builder
                ->setDefaultFormGroup(new FormGroupFullWidth())
                ->addDefaultContainer(new ContainerColumn());
    }

    protected function configureBuilderForSingleCOs(bool $twoColumns = false): void
    {
        $this->builder = (new ConfigurableOptionsBuilder($this));

        $twoColumns ?
            $this->builder
                ->setDefaultFormGroup(new FormGroupHalfWidth())
                ->addDefaultContainer(new RowFluid()) :
            $this->builder
                ->setDefaultFormGroup(new FormGroupFullWidth())
                ->addDefaultContainer(new Container());
    }

    protected function containsGroup(array $configurableOptions): bool
    {
        foreach ($configurableOptions as $configOption)
        {
            if ($configOption instanceof ConfigurableOptionsGroup)
            {
                return true;
            }
        }

        return false;
    }

    protected function generateSwitcher(AbstractConfigurableOption $configOption): Switcher
    {
        $switcher = new Switcher();
        $switcher->setName($configOption->getName());
        $switcher->setTitle($configOption->getFullName());
        $switcher->setDefaultValue(true);

        return $switcher;
    }

    protected function buildSingle(AbstractConfigurableOption $configOption):void
    {
        $this->builder->addField($this->generateSwitcher($configOption));
    }

    protected function buildGroup(ConfigurableOptionsGroup $configOptionGroup):void
    {
        $section = new ConfigOptionsGroupWidget();

        $section->setTitle($this->translate($configOptionGroup->getName()));

        $groupBuilder = (new ConfigurableOptionsBuilder($this));
        foreach ($configOptionGroup->getOptions() as $configOptionInGroup)
        {
            $groupBuilder->addFieldInContainer($section, $this->generateSwitcher($configOptionInGroup));
        }

        $this->builder->addField($section);
    }

}