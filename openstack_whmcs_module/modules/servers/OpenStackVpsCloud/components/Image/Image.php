<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Image;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\UrlTrait;
use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;

class Image extends Container
{
    use UrlTrait;

    public const COMPONENT = 'Image';

    public function __construct()
    {
        parent::__construct();

        $this->setTranslations([
            'no_image',
        ]);
    }
}
