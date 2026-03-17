<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Messages;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\ModuleSettings as Settings;

class TimezoneChecker
{
    public static function updateTimezone():void
    {
        ModuleSettings::update(Settings::CURRENT_TIMEZONE, date_default_timezone_get());
    }

    public static function check():void
    {
        $currentTimezone = date_default_timezone_get();

        if (trim(ModuleSettings::get(Settings::CURRENT_TIMEZONE, $currentTimezone)) == $currentTimezone)
        {
            return;
        }

        Messages::alert(Translator::get("packages.queue.checkers.mismatchedTimeZone"));
    }
}