<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadParent;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Providers\ConfigurationProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services\PriorityJobs;

class EditConfigurationForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ConfigurationProvider::class;
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        $this->builder
            ->addField((new Switcher())
                ->setName('auto_prune')
                ->onChange(new Reload($this)), true);

        if ($this->provider()->getValueById('auto_prune'))
        {
            $this->builder->addField((new Number())
                ->setName('auto_prune_older_than')
                ->setRange(0, 9999)
                ->setDefaultValue(0),
                true);
        }

        if (!Config::get(ConfigSettings::DISABLE_GUIDE, false) &&
            !Config::get(ConfigSettings::DISABLE_CRON_INFO_HINT, false))
        {
            $this->builder
                ->addField((new Switcher())
                    ->setName('show_cron_info')
                    ->setDefaultValue(true), true);
        }

        if ($values = (new PriorityJobs())->getOptions())
        {
            $this->builder
                ->addField((new Dropdown())
                    ->setName('queue_priority')
                    ->setOptions($values)
                    ->setMultiple(), true);
        }
    }
}
