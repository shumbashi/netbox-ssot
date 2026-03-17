<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Source;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Parser\FieldParser;

interface FieldsBuilderInterface
{
    public function setService(?Service $service): self;
    public function build(): self;
    public function getParser(): FieldParser;
}