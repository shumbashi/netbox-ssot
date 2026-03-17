<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\News;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;

class News extends AbstractComponent
{
    use ComponentsContainerTrait;

    public const COMPONENT = 'News';

    public function addItem(NewsItem $newsItem) :self
    {
        $this->addElement($newsItem->toArray());

        return $this;
    }
}
