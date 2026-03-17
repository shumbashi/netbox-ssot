<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;

class UsernameDecorator
{
    const DEFAULT_USERNAME = 'root';
    const USERNAME_OPTION_NAME = 'username';
    public static function decorate(?array $appConfig)
    {
        $username = Arr::get($appConfig, self::USERNAME_OPTION_NAME);
        if (!empty($username)) {
            return $username;
        }

        return self::DEFAULT_USERNAME;
    }
}
