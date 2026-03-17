<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\Parser;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;

class ParserHistoryStack
{
    public static array $stack = [];
    public static function push(string $name, $value)
    {
        self::$stack[$name] = $value;
    }

    public static function exists(string $name)
    {
        return Arr::exists(self::$stack, $name, null);
    }

    public static function get(string $name)
    {
        return Arr::get(self::$stack, $name, null);
    }

    public static function allocate(string $name)
    {
        return Arr::set(self::$stack, $name,null);
    }

    public static function clear()
    {
        self::$stack = [];
    }
}