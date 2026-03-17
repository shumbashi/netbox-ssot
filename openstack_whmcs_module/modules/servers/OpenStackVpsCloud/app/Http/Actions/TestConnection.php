<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators\ConnectionErrorTranslator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;
use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Server\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use WHMCS\Database\Capsule as DB;

class TestConnection extends Action
{
    /**
     * @var Tenant
     */
    protected $tenant;

    public function execute($params = null)
    {
        $translator = new ConnectionErrorTranslator();

        try {
            /*Server id passed in params is 0*/
            $params['serverid'] = (int)Request::get('serverid', 0);
            $configuration = json_decode(html_entity_decode($params['serveraccesshash']), true);
            if (!is_array($configuration) || empty($configuration)) {
                return ['error' => $translator->getTranslated('configuration_not_found')];
            }

            $this->tenant = Factory::getTenantFromTestConnection($params);
            $this->tenant->connect();

            /*Attempt request on scoped tenant*/
            Api::getInstance()->identity()->getProject($configuration['tenantId']);
            Api::getInstance()->compute()->listFlavors();

            return ['success' => true];
        }
        catch (OpenStackApiException $ex)
        {
            if ($ex->getCode() == 401) {
                return ['error' => $translator->getTranslated('auth_error')];
            }

            return ['error' => $ex->getMessage()];
        }
        catch (\Throwable $t)
        {
            Logger::critical(LoggerMessages::EXCEPTION, [
                'server' => $params['serverid'],
                'message' => $t->getMessage(),
                'stacktrace' => $t->getTraceAsString()
            ]);

            return ['error' => $t->getMessage()];
        }
    }
}
