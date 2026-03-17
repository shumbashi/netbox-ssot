<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\App\Http\Actions\MetaData;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Container\ContainerContentCentered;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\TableSimple\Record\Record;
use ModulesGarden\OpenStackVpsCloud\Components\TableSimple\TableSimple;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Exceptions\UserException;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ServersGroups;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers\ProductConfiguration as ProductConfigurationHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptionsGroups\ConfigurableOptionsGroup;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Formatters\ConfigOptionFullNameFormatter;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Modals\CreateConfigurableOptions;

class ProductConfiguration extends \ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm implements AdminAreaInterface
{
    use TranslatorTrait;

    protected string $provider = \ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers\ProductConfiguration::class;

    public function preLoadHtml(): void
    {
        $this->checkServerRequirements();

        $this->builder = BuilderCreator::twoColumns($this);
        $this->setContainerTag('div');

        parent::preLoadHtml();
    }


    private function checkServerRequirements(): void
    {
        if (!Arr::get((new MetaData())->execute(), 'RequiresServer', false))
        {
            return;
        }

        $serverGroupId = Request::get('servergroup', false);

        if (!$serverGroupId)
        {
            throw new UserException($this->translate('productRequiresServer', [], ['packages.product.errors']));
        }

        $moduleName = ModuleConstants::getModuleName();

        if (ServersGroups::find($serverGroupId)->servers->where('type', $moduleName)->count() <= 0)
        {
            $moduleTitle = Config::get('configuration.systemName');

            throw new UserException($this->translate('invalidServerType', ['moduleName' => $moduleTitle], ['packages.product.errors']));
        }
    }

    public function postLoadHtml(): void
    {
        parent::postLoadHtml();

        if (ProductConfigurationHelper::isRunAsProductAddon())
        {
            return;
        }

        $configurableOptions = is_callable(Config::get(ConfigSettings::CONFIG_OPTIONS_LOADER)) ? Config::get(ConfigSettings::CONFIG_OPTIONS_LOADER)(Request::get('id')) : Config::get(ConfigSettings::CONFIG_OPTIONS);
        if (empty($configurableOptions))
        {
            return;
        }

        $widget = new Widget();
        $widget->setTitle($this->translate('title', [], ['packages.product.productConfiguration.form']));

        $table = new TableSimple();

        foreach ($configurableOptions as $configOption)
        {
            if ($configOption instanceof ConfigurableOptionsGroup)
            {
                foreach ($configOption->getOptions() as $option)
                {
                    $table->addRecord(new Record([ConfigOptionFullNameFormatter::buildFullNameContainer($option->getFullName())]));
                }
                continue;
            }

            $table->addRecord(new Record([ConfigOptionFullNameFormatter::buildFullNameContainer($configOption->getFullName())]));
        }

        $widget->addElement($table);

        $button = new ButtonSuccess();
        $button->setTitle($this->translate('button_submit', [], ['packages.product.productConfiguration.form']));
        $button->onClick(new ModalLoad(new CreateConfigurableOptions()));

        $container = new ContainerContentCentered();
        $container->addElement($button);
        $widget->addElement($container);

        $this->addElement($widget);
    }
}