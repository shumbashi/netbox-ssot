<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Providers\ConfigFormProvider;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\AaFeaturesWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\BackupSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\CaFeaturesWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\ConsoleSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\FirewallSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\FlavorSpecificationWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\MetadataSectionWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\MiscellaneousSectionWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\NetworkSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\ProjectSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\SendCustomWelcomeEmailWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\SnapshotSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Widgets\VmSettingsWidget;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\ProductConfiguration\Pages\AppConfigurationWidget;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms\ProductConfiguration;

class ConfigForm extends ProductConfiguration implements AdminAreaInterface, AjaxComponentInterface
{
    const ID = 'config_form';
    protected string $provider = ConfigFormProvider::class;

    public function loadHtml(): void
    {
        $this->setId(self::ID);


        $refreshResourcesForm = new RefreshResourcesForm();
        $this->addElement($refreshResourcesForm);

        $this->addElement((new ProjectSettingsWidget())->addToToolbar((new ButtonInfo())
                ->setName('refresh_resources')
                ->setTitle($this->translate('refresh_resources'))
                ->onClick((new FormSubmit($refreshResourcesForm)))));

        $this->addElement(new AppConfigurationWidget());
        $this->addElement(new NetworkSettingsWidget());
        $this->addElement(new BackupSettingsWidget());
        $this->addElement(new SnapshotSettingsWidget());
        $this->addElement(new VmSettingsWidget());
        $this->addElement(new FirewallSettingsWidget());
        $this->addElement(new ConsoleSettingsWidget());
        $this->addElement(new CaFeaturesWidget());
        $this->addElement(new AaFeaturesWidget());
        $this->addElement(new FlavorSpecificationWidget());
        $this->addElement(new SendCustomWelcomeEmailWidget());
        $this->addElement(new MiscellaneousSectionWidget());
    }
}
