<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper;

class ModuleVersionComparator
{
    public static function compareModuleVersions(string $versionA, string $versionB): int
    {
        $parsedA = self::parseModuleVersion($versionA);
        $parsedB = self::parseModuleVersion($versionB);

        $coreCompare = version_compare($parsedA['core'], $parsedB['core']);

        if ($coreCompare !== 0)
        {
            return $coreCompare;
        }

        $patchCompare = $parsedA['patch'] <=> $parsedB['patch'];

        if ($patchCompare !== 0)
        {
            return $patchCompare;
        }

        return $parsedA['revision'] <=> $parsedB['revision'];
    }

    public static function getMaxVersion(string ...$versions): string
    {
        if (empty($versions))
        {
            throw new \InvalidArgumentException('No versions provided for comparison');
        }

        $max = array_shift($versions);

        foreach ($versions as $version)
        {
            if (self::compareModuleVersions($version, $max) > 0)
            {
                $max = $version;
            }
        }

        return $max;
    }

    public static function getMinVersion(string ...$versions): string
    {
        if (empty($versions))
        {
            throw new \InvalidArgumentException('No versions provided for comparison');
        }

        $min = array_shift($versions);

        foreach ($versions as $version)
        {
            if (self::compareModuleVersions($version, $min) < 0)
            {
                $min = $version;
            }
        }

        return $min;
    }

    protected static function parseModuleVersion(string $version): array
    {
        $core     = $version;
        $patch    = 0;
        $revision = 0;

        if (preg_match('/^([0-9]+\.[0-9]+\.[0-9]+)(?:-p(\d+))?(?:-v(\d+))?$/', $version, $matches))
        {
            $core     = $matches[1];
            $patch    = isset($matches[2]) ? (int)$matches[2] : 0;
            $revision = isset($matches[3]) ? (int)$matches[3] : 0;
        }

        return [
            'core'     => $core,
            'patch'    => $patch,
            'revision' => $revision
        ];
    }
}