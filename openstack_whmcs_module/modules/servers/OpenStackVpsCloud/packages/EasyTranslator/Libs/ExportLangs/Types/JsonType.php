<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\Types;

use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\ArrayData;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\JsonExporter;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\Source\ExportTypeInterface;

class JsonType implements ExportTypeInterface
{
    public function getName(): string
    {
        return 'json';
    }

    public function export(ArrayData $data, string $targetPath):void
    {
        (new JsonExporter($data))
            ->write(new \SplFileInfo($targetPath));
    }
}