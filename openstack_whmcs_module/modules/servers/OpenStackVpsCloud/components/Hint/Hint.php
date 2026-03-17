<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Hint;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\DescriptionTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;

class Hint extends AbstractComponent implements AdminAreaInterface
{
    use TitleTrait;
    use DescriptionTrait;
    use ComponentsContainerTrait;

    public const COMPONENT = 'Hint';

    public const TYPE_DEFAULT   = 'default';
    public const TYPE_SUCCESS   = 'success';
    public const TYPE_INFO      = 'info';
    public const TYPE_WARNING   = 'warning';
    public const TYPE_DANGER    = 'danger';

    public const ICONS = [
        self::TYPE_DEFAULT  => 'circle-outline',
        self::TYPE_SUCCESS  => 'check-circle',
        self::TYPE_INFO     => 'information',
        self::TYPE_WARNING  => 'alert',
        self::TYPE_DANGER   => 'alert-octagon'
    ];

    public function setType(string $type): self
    {
        $this->setSlot('type', $type);
        $this->setIcon(Arr::get(self::ICONS, $type, ''));

        return $this;
    }

    public function setIcon(string $icon): self
    {
        $this->setSlot('icon', $icon);

        return $this;
    }
}