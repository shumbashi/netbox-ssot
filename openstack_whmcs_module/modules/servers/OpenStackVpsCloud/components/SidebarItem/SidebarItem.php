<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\SidebarItem;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\UrlTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;

class SidebarItem extends AbstractComponent implements ComponentContainerInterface
{
    use CssContainerTrait;
    use ComponentsContainerTrait;
    use TitleTrait;
    use UrlTrait;

    public const COMPONENT = 'SidebarItem';

    public function __construct(string $title = "", string $url = "")
    {
        parent::__construct();
        $this->setTitle($title);
        $this->setUrl($url);
    }

    public function setActive(bool $active): self
    {
        $this->setSlot('active', $active);

        return $this;
    }

    public function getUrl():string
    {
        return $this->getSlot('url');
    }

}