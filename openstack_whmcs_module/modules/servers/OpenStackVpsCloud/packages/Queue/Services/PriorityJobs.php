<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Services;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\JobNameTranslator;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ConfigSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ModuleSettings as Settings;

class PriorityJobs
{
    public function getOptions() : ?array
    {
        $options = [];
        $values = Config::get(ConfigSettings::JOBS_PRIORITY_VALUES, false);

        if (!$values) {
            return null;
        }

        foreach ($values as $value) {
            $options[$value] = (new JobNameTranslator())->format($value);
        }

        return $options;
    }

    public function getPreparedSqlQueue() : string
    {
        $jobs_values = ModuleSettings::get(Settings::QUEUE_PRIORITY, []);

        $escapedNames = array_map(function($name)
        {
            $name = str_replace("'", "''", $name);
            $name = str_replace('\\', "\\\\", $name);
            return "'{$name}'";
        }, array_reverse($jobs_values));

        return implode(',', $escapedNames);
    }
}