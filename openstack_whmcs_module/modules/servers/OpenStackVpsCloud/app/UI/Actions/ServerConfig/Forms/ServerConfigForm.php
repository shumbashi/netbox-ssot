<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ServerConfig\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ServerConfig\Providers\ServerConfigProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroupHalfWidth;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputGroup\FormInputGroup;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\Row\Row;
use ModulesGarden\OpenStackVpsCloud\Components\RowFluid\RowFluid;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Decorator\Decorator;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms\ServerConfiguration;

class ServerConfigForm extends ServerConfiguration implements AdminAreaInterface
{
    const ID = 'server_config_form';
    protected string $provider = ServerConfigProvider::class;

    public function loadHtml(): void
    {
        $this->setId(self::ID);

        $this->provider()->read();

        $this->builder = BuilderCreator::twoColumns($this);

        $this->builder->createField(HiddenField::class, 'configservers[hostname]');
        $this->builder->createField(HiddenField::class, 'configservers[ipaddress]');
        $this->builder->createField(HiddenField::class, 'configservers[password]');
        $this->builder->createField(HiddenField::class, 'configservers[username]');
        $this->builder->createField(HiddenField::class, 'configservers[secure]');

        $path = (new FormInputText())->setName("serverconfig[path]");
        $loadApiVersionButton  = (new ButtonInfo())
            ->setTitle($this->translate('load_versions.title'))
            ->setIcon('refresh')
            ->onClick(
                (new FormSubmit($this))->setCustomAction(ServerConfigProvider::ACTION_REFRESH_IDENTITY)
            );

        $group = new FormInputGroup();
        $group->addElement($path);
        $group->addElement($loadApiVersionButton);
        $group->setName('serverconfig[path]');

        $this->builder->addField($group);

        if (empty($this->provider()->getAvailableValuesById('serverconfig[apiVersion]'))) {
            return;
        }

        $apiVersionDropdown = (new Dropdown())
            ->setName("serverconfig[apiVersion]")
            ->onChange(new Reload($this))
            ->required();

        $this->builder->addField($apiVersionDropdown);

        if (empty($this->provider()->getValueById('serverconfig[apiVersion]'))) {
            return;
        }

        $this->builder->addField((new FormInputText())->setName("serverconfig[tenantId]")->required());
        $this->builder->addField((new FormInputText())->setName("serverconfig[domain]")->required());

        $this->builder->addField((new FormInputText())->setName("serverconfig[certificate]"));
        $this->builder->addField((new FormInputText())->setName("serverconfig[projectName]")->required());

        $placeholder = new FormGroupHalfWidth();

        $button = (new ButtonSuccess())
            ->setTitle($this->translate('reload_endpoints.title'))
            ->setIcon('refresh')
            ->onClick(
                (new FormSubmit($this))->setCustomAction(ServerConfigProvider::ACTION_REFRESH_ENDPOINTS)
            );

        $rowFluid = new RowFluid();
        $rowFluid->addElement($button);

        (new Decorator($rowFluid))->columns()->one();

        $placeholder->addElement($rowFluid);

        $this->builder->addElement($placeholder);

        if (empty($this->provider()->getAvailableValuesById('serverconfig[identityEndpoint]'))) {
            return;
        }

        $rowFluid = new Row();
        $regionField = (new FormInputText())
            ->setName("serverconfig[region]")
            ->setDefaultValue("")
            ->setReadOnly();

        $this->builder->addFieldInContainer($rowFluid, $regionField);
        $this->builder->addFieldInContainer($rowFluid, (new Dropdown())->setName("serverconfig[computeEndpoint]")->setDefaultValueAsFirstOption()->required());
        $this->builder->addFieldInContainer($rowFluid, (new Dropdown())->setName("serverconfig[identityEndpoint]")->setDefaultValueAsFirstOption()->required());
        $this->builder->addFieldInContainer($rowFluid, (new Dropdown())->setName("serverconfig[imageEndpoint]")->setDefaultValueAsFirstOption()->required());
        $this->builder->addFieldInContainer($rowFluid, (new Dropdown())->setName("serverconfig[networkEndpoint]")->setDefaultValueAsFirstOption()->required());
        $this->builder->addFieldInContainer($rowFluid, (new Dropdown())->setName("serverconfig[volumeEndpoint]")->setDefaultValueAsFirstOption());
        $this->builder->addFieldInContainer($rowFluid, (new Dropdown())->setName("serverconfig[metricEndpoint]")->setDefaultValueAsFirstOption());
        $this->addElement($rowFluid);
    }

}