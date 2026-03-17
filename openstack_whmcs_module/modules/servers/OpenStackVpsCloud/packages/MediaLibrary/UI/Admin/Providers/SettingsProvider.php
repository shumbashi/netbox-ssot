<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Enums\Settings;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\LibraryPathHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\ModuleSettings\Support\Facades\ModuleSettings;

class SettingsProvider extends CrudProvider
{
    public function read()
    {
        $this->data['libraryPath'] = LibraryPathHelper::getPath();
    }
}