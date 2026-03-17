<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\ImageText;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\UrlTrait;

class ImageText extends Container
{
    use UrlTrait;
    use TextTrait;

    public const COMPONENT = 'ImageText';

    public function __construct()
    {
        parent::__construct();

        $this->setTranslations([
            'no_image',
        ]);
    }
}