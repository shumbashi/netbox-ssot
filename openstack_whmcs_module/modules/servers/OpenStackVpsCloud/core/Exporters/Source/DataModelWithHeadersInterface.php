<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Exporters\Source;

interface DataModelWithHeadersInterface extends DataModelInterface
{
    public function setCustomHeaders(array $headers):void;
    public function getHeaders(): array;
    public function getContentData();
    public function getItemValuesByKey(int $key);
}