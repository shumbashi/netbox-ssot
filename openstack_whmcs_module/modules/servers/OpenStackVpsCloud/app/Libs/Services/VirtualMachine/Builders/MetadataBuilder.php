<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

class MetadataBuilder
{
    public function build(?string $metadata): array
    {
        if (!empty($metadata))
        {
            return $this->convertMetadata($metadata);
        }

        return [];
    }

    private function convertMetadata(string $metadata)
    {
        $metadataArray = [];
        foreach(explode(',', $metadata) as $row)
        {
            if(strpos($row, '=') === false)
            {
                continue;
            }

            $data = explode('=', $row, 2);
            if(empty($data) || empty(trim($data[0])))
            {
                continue;
            }

            $metadataArray[trim($data[0])] = trim($data[1]);
        }

        return $metadataArray;
    }
}