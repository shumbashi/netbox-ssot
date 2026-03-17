<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;

class MassGroupProvider extends CrudProvider
{
    public function delete()
    {
        $ids = explode(',', $this->formData['id']);
        Group::whereIn('id', $ids)->get()->each(function ($group) {
            $group->delete();
        });
    }
}