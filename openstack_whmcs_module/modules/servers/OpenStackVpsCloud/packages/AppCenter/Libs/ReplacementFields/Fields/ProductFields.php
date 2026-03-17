<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Fields;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product;

class ProductFields extends BaseFields
{
    const NAME = 'product';
    protected $model = Product::class;
}