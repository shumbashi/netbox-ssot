<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Http;

use ModulesGarden\OpenStackVpsCloud\Core\Traits\IsAdmin;
use ModulesGarden\OpenStackVpsCloud\Core\Traits\OutputBuffer;

/**
 * Description of AbstractController
 */
class AbstractController
{
    use IsAdmin;
    use OutputBuffer;
}
