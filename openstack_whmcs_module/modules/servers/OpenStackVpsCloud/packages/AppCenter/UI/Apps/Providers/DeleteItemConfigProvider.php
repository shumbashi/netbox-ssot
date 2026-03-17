<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;

class DeleteItemConfigProvider extends CrudProvider
{
    public function delete()
    {
        ItemConfig::where('id', $this->formData->get('id'))
            ->delete();
    }
}