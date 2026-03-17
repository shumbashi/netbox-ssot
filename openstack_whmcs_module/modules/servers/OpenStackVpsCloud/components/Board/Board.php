<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Board;

use ModulesGarden\OpenStackVpsCloud\Components\BoardColumn\BoardColumn;
use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Components\DataBuilder;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxDataProviderTrait;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;

class Board extends Container
{
    public const COMPONENT = 'Board';

    use AjaxDataProviderTrait;

    public function __construct()
    {
        parent::__construct();

        $this->providerAction = CrudProvider::ACTION_READ;
    }

    protected function processReadAction(string $providerAction)
    {
        return new Response(
            (new DataBuilder($this))
                ->withHtml()
                ->withData()
                ->toArray()
        );
    }

    public function addColumn(BoardColumn $column): self
    {
        $this->addComponent('columns', $column);

        return $this;
    }
}
