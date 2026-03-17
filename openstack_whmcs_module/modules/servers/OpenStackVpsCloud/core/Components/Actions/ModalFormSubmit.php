<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Actions;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractActionInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Components\DataBuilder;

class ModalFormSubmit extends AbstractActionInterface
{
    protected $modal;

    public function __construct($modal)
    {
        $this->modal = $modal;
    }

    public function toArray(): array
    {
        return [
            'action'    => 'modalFormSubmit',
            'elementId' => $this->modal ? $this->modal->getId() : null,
        ];
    }
}
