<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Builder;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Parser\FieldParser;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Source\FieldsBuilderInterface;

abstract class BaseFieldsParserBuilder implements FieldsBuilderInterface
{
    protected ?FieldParser $parser = null;
    protected ?Service $service = null;

    public function __construct()
    {
        $this->parser = new FieldParser();
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;
        return $this;
    }

    public function getParser(): FieldParser
    {
        return $this->parser;
    }

    public abstract function build(): self;
}