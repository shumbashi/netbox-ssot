<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\Types;

use ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels\ArrayData;
use ModulesGarden\OpenStackVpsCloud\Core\Exporters\PhpExporter;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ExportLangs\Source\ExportTypeInterface;

class PhpType implements ExportTypeInterface
{
    public function getName(): string
    {
        return 'php';
    }

    public function export(ArrayData $data, string $targetPath):void
    {
        $iterator = $data->getIterator();

        foreach ($iterator as $key => $value)
        {
            $iterator->offsetSet($key, $this->fixQuotationMarks($value));
        }

        (new PhpExporter(new ArrayData($iterator->getArrayCopy()), '$_LANG'))
            ->write(new \SplFileInfo($targetPath));
    }

    protected function fixQuotationMarks(string $value = ""): string
    {
        return '"' . preg_replace('/(["\'])/', '\\\\$1', trim($value, "'\"")) . '"';
    }
}