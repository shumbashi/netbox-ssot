<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Actions;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractActionInterface;

class Redirect extends AbstractActionInterface
{
    protected string $url = '';
    protected string $target = '';
    protected string $type = 'simple';
    protected array $targetData = [];
    protected array $params = [];

    public function __construct(string $url, array $params = [])
    {
        $this->url    = $url;
        $this->params = array_map(function($param) {
            return (string)$param;
            }, $params);
    }

    public function openNewWindow(array $data = []): self
    {
        $this->target = 'new';
        $this->targetData = $data;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'action'        => 'redirect',
            'target'        => $this->target,
            'targetData'    => $this->getParsedTargetData(),
            'url'           => $this->url,
            'params'        => $this->params,
            'type'          => $this->type
        ];
    }

    public function getParsedTargetData(): string
    {
        return implode(",", array_map(function ($property, $value) {
            return $property . "=" . $value ;
        }, array_keys($this->targetData), $this->targetData));
    }
}
