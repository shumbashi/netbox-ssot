<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\AbstractConfigurableOption;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptionsGroups\ConfigurableOptionsGroup;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ConfigurableOptions;
use \ModulesGarden\OpenStackVpsCloud\Core\Routing\Url;

class CreateConfigurableOptions extends CrudProvider
{
    public function create()
    {
        $product        = Product::findOrFail(Request::get('id'));
        $productService = new ConfigurableOptions($product);

        foreach ($this->getAllConfigurableOptions() as $configOption)
        {
            /**
             * @var $configOption AbstractConfigurableOption
             */
            if ($this->formData->get($configOption->getName(), false))
            {
                $productService->createConfigurableOption($configOption);
            }
        }

        return (new Response())
            ->setSuccess($this->translate('create_success'))
            ->setActions([
                new Redirect($this->getConfigurableOptionsTabUrl()),
                new ModalClose()
            ]);
    }

    protected function getAllConfigurableOptions(): array
    {
        $configurableOptionsFromConfig = is_callable(Config::get(ConfigSettings::CONFIG_OPTIONS_LOADER)) ?
            Config::get(ConfigSettings::CONFIG_OPTIONS_LOADER)(Request::get('id')) :
            Config::get(ConfigSettings::CONFIG_OPTIONS);

        $configurableOptions = [];

        foreach ($configurableOptionsFromConfig as $configOption)
        {
            if ($configOption instanceof ConfigurableOptionsGroup)
            {
                $configurableOptions = array_merge($configurableOptions, $configOption->getOptions());
                continue;
            }
            $configurableOptions[] = $configOption;
        }

        return $configurableOptions;
    }

    protected function getConfigurableOptionsTabUrl():string
    {
        return Url::adminarea('configproducts.php', ['action' => 'edit', 'id' => Request::get('id')]) . '#tab=5';
    }
}