<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Validation\Rule;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ModuleSettings as Settings;

class ConfigurationProvider extends CrudProvider
{
    public function read()
    {
        $this->data['auto_prune']            = $this->formData->get('auto_prune', ModuleSettings::get(Settings::AUTO_PRUNE));
        $this->data['auto_prune_older_than'] = ModuleSettings::get(Settings::AUTO_PRUNE_OLDER_THAN);
        $this->data['show_cron_info']        = $this->formData->get('show_cron_info', ModuleSettings::get(Settings::SHOW_CRON_INFO));
        $this->data['queue_priority']        = $this->formData->get('queue_priority', ModuleSettings::get(Settings::QUEUE_PRIORITY));
    }

    public function update()
    {
        $this->validate(
            $this->formData->toArray(),
            [
                'auto_prune'            => ['sometimes', Rule::in(0, 1)],
                'auto_prune_older_than' => ['required_if:auto_prune,1', 'numeric', 'min:1']
            ],
            [
                'auto_prune' => [1]
            ]
        );

        ModuleSettings::save([
            Settings::AUTO_PRUNE            => $this->formData->get('auto_prune'),
            Settings::AUTO_PRUNE_OLDER_THAN => $this->formData->get('auto_prune_older_than', 0),
            Settings::SHOW_CRON_INFO        => $this->formData->get('show_cron_info', 0),
            Settings::QUEUE_PRIORITY        => $this->formData->get('queue_priority', [])
        ]);
    }
}
