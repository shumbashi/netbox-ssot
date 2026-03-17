<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Actions;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\Modal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractActionInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Traits\WithParamsTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\DataBuilder;

class ModalLoad extends AbstractActionInterface
{
    use WithParamsTrait;

    protected Modal $modal;

    public function __construct(Modal $modal)
    {
        $this->modal = $modal;
    }

    public function toArray(): array
    {
        return [
            'action'       => 'modalLoad',
            'modal'        => (new DataBuilder($this->modal))->toArray(),
            'params'       => $this->ajaxData,
        ];
    }
}
