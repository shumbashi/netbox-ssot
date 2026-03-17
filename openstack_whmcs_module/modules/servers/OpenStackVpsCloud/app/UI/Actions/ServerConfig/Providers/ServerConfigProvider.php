<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ServerConfig\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\ServerParamsBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators\ConnectionErrorTranslator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ServerConfig;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers\ServerConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ServerConfiguration as ServerConfigurationService;

class ServerConfigProvider extends ServerConfiguration
{
    const ACTION_REFRESH_ENDPOINTS = 'refreshEndpoints';
    const ACTION_REFRESH_IDENTITY = 'refreshIdentity';

    public function read()
    {
        if (!Request::get('id')) {
            throw new \Exception((new ConnectionErrorTranslator())->getTranslated('save_configuration_first'));
        }

        $servers = (new Servers());
        $servers->createTableIfNotExist();
        $servers->extendColumnLengthIfTooSmall();

        $isReload = Request::get('providerAction') === AbstractForm::ACTION_RELOAD_FORM;
        if (!$isReload) {
            parent::read();
        } else {
            $this->data = $this->formData;
        }

        $extendedConfiguration = $this->data->get('serverconfig');
        if (empty($extendedConfiguration)) {
            return;
        }

        $standardConfiguration = $this->data->get('configservers');
        $serverParams = ServerParamsBuilder::fromFormData($standardConfiguration, Request::get('id', null));

        try {
            $this->setIdentityDetails($extendedConfiguration, $serverParams);
        } catch (\Exception $ex) {
            $this->unsetApiVersions();
        }

        try {
            $this->setEndpoints($serverParams, $extendedConfiguration);
        } catch (\Exception $t) {
            $this->unsetEndpoints();
        }
    }

    public function create()
    {
        $response = parent::create();

        $serverId = (int)Request::get('id');

        $configuration = (new ServerConfigurationService($serverId))->get();

        try {
            $serverConfigManager = new ServerConfig();
            $serverConfigManager->createOrUpdateServer($configuration, $serverId);
            $serverConfigManager->setEndpoints($serverId, $configuration);
        } catch (\Throwable $ex) {
            Logger::critical(LoggerMessages::EXCEPTION, [
                'server' => $serverId,
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
        }

        return $response;
    }

    public function refreshIdentity()
    {
        $this->data = $this->formData;

        $extendedConfiguration = $this->data->get('serverconfig');
        if (empty($extendedConfiguration)) {
            return;
        }

        $this->validate($this->formData->toArray(), [
            'serverconfig[path]' => ['required'],
        ]);

        $standardConfiguration = $this->data->get('configservers');
        $params = ServerParamsBuilder::fromFormData($standardConfiguration, Request::get('id', null));
        $response = (new Response())
            ->setActions([
                (new ReloadById('server_config_form'))
            ]);

        try {
            $this->setIdentityDetails($extendedConfiguration, $params);
        } catch (\Exception $ex) {
            return $response->setError((new ConnectionErrorTranslator())->getTranslated('auth_error_identity'));
        }

        return $response;
    }

    public function refreshEndpoints()
    {
        $this->data = $this->formData;

        $configuration = $this->data->get('serverconfig');
        if (empty($configuration)) {
            return;
        }

        $this->validate($this->formData->toArray(), [
            'serverconfig[tenantId]' => ['required'],
            'serverconfig[path]' => ['required'],
            'serverconfig[apiVersion]' => ['required'],
            'serverconfig[domain]' => ['required'],
            'serverconfig[projectName]' => ['required'],
        ]);

        $response = (new Response())
            ->setActions([
                (new ReloadById('server_config_form'))
            ]);

        try {
            $standardConfiguration = $this->data->get('configservers');
            $serverParams = ServerParamsBuilder::fromFormData($standardConfiguration, Request::get('id', null));
            if (!empty($configuration['region'])) {
                $serverParams['region'] = $configuration['region'];
            }
            
            $this->setEndpoints($serverParams, $configuration);
        } catch (\Exception $t) {
            return $response->setError((new ConnectionErrorTranslator())->getTranslated('auth_error'));
        }

        return $response;
    }

    protected function setIdentityDetails(array $auth, array $server)
    {
        $server = array_merge($server, $auth);
        $identity = (new ServerConfig())->getIdentityVersions($server);
        if (empty($identity)) {
            throw new \Exception('identity_version_not_found');
        }

        $this->availableValues['serverconfig[apiVersion]'] = array_combine($identity, $identity);
    }

    protected function setEndpoints(array $params, array $config)
    {
        $required = ['tenantId', 'projectName', 'domain', 'apiVersion'];
        foreach ($required as $value) {
            if (empty($config[$value])) {
                return;
            }
        }

        $endpoints = (new ServerConfig())->getEndpoints(Request::get('id'), $params, $config);

        if (!empty($endpoints) && isset($endpoints['region'])) {

            $regions = [];
            foreach ($endpoints['region'] as $key => $value) {
                $regions[] = $value['nodeID'];
            }
            
            $this->data['serverconfig[region]'] = implode(', ', $regions);
            
            $selectedRegion = $config['region'] ?? reset($regions);
            if (!empty($selectedRegion)) {
                $config['region'] = $selectedRegion;
            }

            unset($endpoints['region']);
        }

        foreach ($endpoints as $endpoint => $values) {

            $options = [];

            foreach ($values as $value) {
                $value['selected'] = 0;
                $options[base64_encode(json_encode($value))] = $value['interface'];
            }

            $this->availableValues[sprintf("serverconfig[%sEndpoint]", $endpoint)] = $options;
        }
    }

    protected function unsetEndpoints(): void
    {
        foreach ($this->data->get('configuration', []) as $name => $value) {
            if (str_contains($name, 'Endpoint')) {
                $this->data[sprintf('serverconfig[%s]', $name)] = '';
                $this->availableValues[sprintf('serverconfig[%s]', $name)] = [];
            }
        }
    }

    protected function unsetApiVersions(): void
    {
        $this->data['serverconfig[apiVersion]'] = '';
        $this->availableValues['serverconfig[apiVersion]'] = [];
    }
}