<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Others;

use ModulesGarden\OpenStackVpsCloud\Components\Label\Label;

abstract class TypeLabel
{
    abstract protected function getConfig(string $type):array;
    abstract protected function translateStatus(string $type):string;

    public function create(string $type)
    {
        $config = $this->getConfig($type);

        $label = new Label();
        $label->setText($this->translateStatus($type));
        $label->displayAsStatusLabel();

        if (isset($config['type']))
        {
            $label->setType($config['type']);
        }

        return $label;
    }
}
