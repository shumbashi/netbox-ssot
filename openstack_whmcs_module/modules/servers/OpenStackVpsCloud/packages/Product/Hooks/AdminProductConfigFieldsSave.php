<?php

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers\RequiredCustomFieldsGenerator;
use Illuminate\Database\Capsule\Manager as DB;

$hookManager->register(
    function($params) {
        if (!\ModulesGarden\OpenStackVpsCloud\Packages\Product\Helpers\ProductConfiguration::isSupportedInRequest())
        {
            return;
        }

        RequiredCustomFieldsGenerator::addRequiredProductCustomFields($params['pid']);

        if (empty(Request::get('customconfigoption', [])))
        {
            return;
        }

        try
        {
            DB::beginTransaction();

            (new ProductConfiguration($params['pid']))
                ->flush()
                ->save(Request::get('customconfigoption'));

            DB::commit();

        } catch (\Exception $exception)
        {
            DB::rollback();
        }

    }, 1001);
