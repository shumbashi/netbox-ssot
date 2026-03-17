<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Order;

class OrderFields extends BaseFields
{
    const NAME = 'order';
    protected $model = Order::class;
}