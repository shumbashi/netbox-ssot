<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Helper;

use ModulesGarden\OpenStackVpsCloud\Core\Traits\OutputBuffer;

class OutputBufferHelper
{
    use OutputBuffer;

    public static function clean(): void
    {
        (new self())->cleanOutputBuffer();
    }
}