<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\Source;

use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\ArrayData;

interface ExportTypeInterface
{
    public function getName():string;
    public function export(ArrayData $data, string $targetPath):void;
}