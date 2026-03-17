<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Forms\ConfigForm;
use ModulesGarden\OpenStackVpsCloud\Core\UI\ViewConfigOptions;

class ConfigOptions extends \ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Server\ConfigOptions
{
    public function execute($params = null)
    {
        return (new ViewConfigOptions())->addElement(new ConfigForm());
    }
}
