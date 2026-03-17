<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories;

use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\App\AppInterface;

class AppFactory
{
    public static function factory(string $class): ?AppInterface
    {
        $instance = new $class;
        if ($instance instanceof AppInterface) {
            return $instance;
        }

        throw new \Exception(sprintf('Class %s must implement %s', $class, AppInterface::class));
    }
}

