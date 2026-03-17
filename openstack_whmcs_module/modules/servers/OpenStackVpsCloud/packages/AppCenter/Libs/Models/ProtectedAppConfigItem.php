<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models;

use Illuminate\Contracts\Support\Arrayable;

class ProtectedAppConfigItem extends AppConfigItem
{
    protected ?bool $protected = true;
}
