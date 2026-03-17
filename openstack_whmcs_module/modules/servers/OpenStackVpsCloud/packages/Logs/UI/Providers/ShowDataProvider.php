<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;

class ShowDataProvider extends CrudProvider
{
    public function read()
    {
        $this->data->createFrom(Logs::where('id', $this->formData->get('id'))->select('data')->first()->toArray());
    }
}
