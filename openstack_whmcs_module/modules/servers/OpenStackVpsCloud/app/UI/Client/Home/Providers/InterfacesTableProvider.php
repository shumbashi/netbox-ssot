<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\DeletingIP;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\di;

class InterfacesTableProvider extends CrudProvider
{
    public function read()
    {
        parent::read();
    }

    public function delete()
    {
        Queue::push(DeletingIP::class,
            [
                'hid' => Params::get('serviceid'),
                'pid' => Params::get('pid'),
                'data' => json_decode(base64_decode($this->formData['id']), true)
            ],
            'default',
            null,
            'Hosting',
            Params::get('serviceid'));
    }

    public function update()
    {

    }
}