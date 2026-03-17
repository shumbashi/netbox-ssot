<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support;

/**
 * Class Text
 *
 * Utility class for string transformations and format conversions.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Support
 */
class Text
{
    /**
     * Convert a class name or namespace to a namespace-compatible string.
     * Replaces backslashes with underscores.
     *
     * @param string|object $class Class name, namespace, or object instance
     * @return string Namespace-compatible string
     */
    public static function toNamespace($class)
    {
        $name = is_string($class) ? $class : get_class($class);

        return str_replace('\\', '_', $name);
    }

    /**
     * Convert a camelCase or PascalCase string to snake_case.
     *
     * @param string $input The string to convert
     * @return string Converted string in snake_case format
     */
    public static function toUnderscore($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    /**
     * Convert a camelCase or PascalCase string to kebab-case.
     *
     * @param string $input The string to convert
     * @return string Converted string in kebab-case format
     */
    public static function toKebab($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $input));
    }
}
