<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Options;

use Illuminate\Contracts\Support\Arrayable;
use ModulesGarden\OpenStackVpsCloud\Components\Graph\Constants\Colors;

class ColorsOptions implements Arrayable
{
    public array $colors;

    public function __construct(array $colors = [])
    {
        $this->colors = $colors ?: Colors::COLORS_SET;
    }

    public function toArray(): array
    {
        return $this->colors;
    }
}