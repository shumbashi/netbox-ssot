<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\DataProviders;

use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\Converter\ArrayFormat;

class DataContainer extends Container
{
    public function get($name, $default = null)
    {
        return parent::get(ArrayFormat::parseKeyToDotedFormat($name), $default);
    }

    public function set($name, $value): self
    {
        return parent::set(ArrayFormat::parseKeyToDotedFormat($name), $value);
    }

    public function push($name, $value): self
    {
        return parent::push(ArrayFormat::parseKeyToDotedFormat($name), $value);
    }

    public function delete($name): self
    {
        return parent::delete(ArrayFormat::parseKeyToDotedFormat($name));
    }
}