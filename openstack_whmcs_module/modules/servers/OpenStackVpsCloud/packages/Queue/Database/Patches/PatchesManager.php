<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\LogActivity;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Source\PatchInterface;

class PatchesManager
{
    public function executeAll():void
    {
        $patches = $this->getAll();

        $executed = [];

        foreach ($patches as $patch)
        {
            $className = __NAMESPACE__ . '\Patches\\' . $patch;

            $this->executePatch($className, $executed);
        }
    }

    protected function getAll(): array
    {
        $path    = __DIR__ . DIRECTORY_SEPARATOR . "Patches";
        $patches = scandir($path);


        $patches = array_filter($patches, function($file) {
            return !in_array($file, ['.', '..']);
        });

        return array_map(function($file) {
            return str_replace('.php', '', $file);
        }, $patches);
    }

    protected function executePatch(string $patchClass, array &$executed):void
    {
        try
        {
            if (!class_exists($patchClass) || in_array($patchClass, $executed))
            {
                return;
            }

            $patchObj = new $patchClass();

            if (!is_subclass_of($patchObj, PatchInterface::class))
            {
                return;
            }

            $requiredPatches = $patchObj->requires();

            if (!empty($requiredPatches))
            {
                foreach ($requiredPatches as $requiredPatch)
                {
                    if (!is_string($requiredPatch))
                    {
                        continue;
                    }

                    $this->executePatch($requiredPatch, $executed);
                }
            }

            $patchObj->execute();

            $executed[] = $patchClass;
        }
        catch (\Exception $ex)
        {
            LogActivity::error($ex->getMessage(), [
                'module'    => ModuleConstants::getModuleName(),
                'patch'     => $patchClass
            ]);
        }
    }

}