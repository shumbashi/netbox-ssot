<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Source\AbstractOption;

class ChartOptions extends AbstractOption
{
    public const ROBOTO_FONT_FAMILY = 'Roboto, sans-serif';
    public const MAIN_TEXT_COLOR    = '#505459';

    public string $type;
    public string $fontFamily;
    public string $foreColor;
    public null|int|string $height = null;
    public null|int|string $width = null;
    public array $toolbar = ['show' => false];
    public array $zoom = ['enabled' => false];

    public function __construct(string $type = 'line')
    {
        $this->type       = $type;
        $this->fontFamily = self::ROBOTO_FONT_FAMILY;
        $this->foreColor  = self::MAIN_TEXT_COLOR;
    }

    public function getAttributes():array
    {
        return array_filter([
            'type'       => $this->type,
            'height'     => $this->height,
            'width'      => $this->width,
            'toolbar'    => $this->toolbar,
            'zoom'       => $this->zoom,
            'fontFamily' => $this->fontFamily,
            'foreColor'  => $this->foreColor,
        ]);
    }
}