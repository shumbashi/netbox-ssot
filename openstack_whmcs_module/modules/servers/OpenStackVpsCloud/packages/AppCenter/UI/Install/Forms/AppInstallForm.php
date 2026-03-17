<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\FieldFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppConfigItemFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Providers\AppInstallProvider;

class AppInstallForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_CREATE;
    protected string $provider = AppInstallProvider::class;

    public function loadHtml(): void
    {
        $this->builder->addField((new HiddenField())->setName('id'));

        $this->provider()->read();

        $configItemsQuery = ItemConfig::where('visible', true)
            ->where('item_id', $this->provider()->getValueById('id'));

        foreach ($configItemsQuery->get() as $item) {

            $configItem = AppConfigItemFactory::forItem($item);

            $field = FieldFactory::forItem($configItem);

            $this->builder->addField($field);
        }
    }
}
