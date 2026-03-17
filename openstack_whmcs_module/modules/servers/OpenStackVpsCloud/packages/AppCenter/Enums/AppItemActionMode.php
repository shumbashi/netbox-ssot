<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums;

class AppItemActionMode
{
    const DELETE_ALL    = 'deleteAll';
    const DELETE_LOADER = 'deleteLoader';
    const UPDATE_APPS   = 'updateApps';

    public function getAll()
    {
        return [
            self::UPDATE_APPS,
            self::DELETE_ALL,
            self::DELETE_LOADER,
        ];
    }
}