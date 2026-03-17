<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Managers;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\ServerConfigurationHelper;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services\EndpointNormalizer;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\Endpoint\Services\EndpointRepository;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Identity;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\ImageModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\NetworkModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\SecurityGroupModel;
use ModulesGarden\OpenStackVpsCloud\App\Models\Servers;
use ModulesGarden\OpenStackVpsCloud\Core\Http\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;

class ServerConfig
{
    const ENDPOINTS_FIELD = [
        Servers::COMPUTE  => 'computeEndpoint',
        Servers::IMAGE    => 'imageEndpoint',
        Servers::IDENTITY => 'identityEndpoint',
        Servers::VOLUME   => 'volumeEndpoint',
        Servers::NETWORK  => 'networkEndpoint',
        Servers::METRIC   => 'metricEndpoint',
    ];

    /**
     * @param array $serverParams
     * @return array|string
     */
    public function getIdentityVersions(array $serverParams)
    {
        $availableVersions = [];
        $versions          = [];

        $protocol          = ($serverParams['serversecure'] == 'on') ? 'https' : 'http';
        $destinationUrl = $protocol . '://' . (empty($serverParams['serverhostname']) ? $serverParams['serverip'] : $serverParams['serverhostname']);
        $destinationUrl .= is_numeric($serverParams['path'])
            ? ':' . $serverParams['path'] . '/'
            : (!empty($serverParams['path']) ? '/' . $serverParams['path'] . '/' : '/');

        $identityVersions = $this->getCurlPageContent($destinationUrl);
        if (empty($identityVersions))
        {
            return [];
        }

        /* Response can be 'versions' or 'version' so lets parse to one form */
        if (isset($identityVersions['versions']))
        {
            $versions = $identityVersions['versions'];
        }
        elseif (isset($identityVersions['version']))
        {
            array_push($versions, $identityVersions['version']);
        }
        else
        {
            return 'error';
        }

        foreach ($versions['values'] as $version)
        {
            foreach ($version['links'] as $link)
            {
                if ($link['rel'] == 'self')
                {
                    $explodedHref = explode('/', trim($link['href'], '/'));
                    $version      = end($explodedHref);
                    array_push($availableVersions, $version);
                }
            }
        }

        return $availableVersions;
    }

    /**
     * @param $url
     * @return mixed
     */
    private function getCurlPageContent(string $url)
    {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlSession, CURLOPT_TIMEOUT, 10);

        curl_exec($curlSession);
        $jsonData = json_decode(curl_exec($curlSession), true);
        curl_close($curlSession);

        return $jsonData;
    }

    /**
     * @param array $params
     * @return array
     * @throws OSException
     * @throws \OSException
     */
    public function getEndpoints(int $serverId, array $params, array $configuration)
    {
        $params = array_merge($params, $configuration);
        $tenant = Tenant::WHMCSFactory($params, true, $params['tenantId']);

        $allRegions = $tenant->api()->getApIdentity()->getRegions();

        $region        = $params['region'] ?: reset($allRegions);
        $endpointsList = $tenant->getEndpointList();
        $options       = [];

        foreach ($tenant->testEndPoints() as $service => $value)
        {
            $options[$service] = $this->getAvailableEndpointsByRegion($endpointsList, $service, $region, $serverId);

            if (empty($options[$service]))
            {
                $options[$service] = $this->getEndpointsFromAnotherRegion($endpointsList, $allRegions, $service, $serverId);
            }
        }

        foreach ($tenant->api()->getApIdentity()->getRegions() as $regionID => $region)
        {
            $options[Servers::REGION][] = ['service' => ucfirst(Servers::REGION), 'nodeID' => $region, 'endpoint' => '', 'interface' => ''];
        }

        return $options;
    }

    private function getAvailableEndpointsByRegion(array $endpointsList, string $service, string $region, int $serverId)
    {
        $optionsForOneService = [];
        $normalizer = new EndpointNormalizer();
        $repository = new EndpointRepository();
        $savedEndpoints = $repository->getByServerId($serverId) ?: [];

        if (in_array($service, [Servers::VOLUME, Servers::METRIC]))
        {
            $optionsForOneService[] = [
                'service'   => ucfirst($service),
                'nodeID'    => '',
                'endpoint'  => '',
                'interface' => '',
            ];
        }

        $normalizedList = $normalizer->normalizeAndFilter($endpointsList);
        foreach ($normalizedList as $value)
        {
            $optionsForOneService = array_merge($optionsForOneService, $this->addAvailableEndpointToArray($value, $region, $service, $savedEndpoints));
        }

        return $optionsForOneService;
    }

    private function addAvailableEndpointToArray(array $value, string $region, string $service, array $savedEndpoints)
    {
        $optionsForOneService = [];

        if ($value['region'] == $region && strpos($value['service'], ucfirst($service)) !== false)
        {
            $endpointsManager = new EndpointsManager($savedEndpoints ?: []);

            $optionsForOneService[] = [
                'service'   => ucfirst($service),
                'nodeID'    => $value['id'],
                'endpoint'  => $value['url'],
                'interface' => $value['interface'],
                'selected'  => !empty($endpointsManager->byService((new EndpointNormalizer())->normalizeServiceName(ucfirst($service)))->byEndpointType($value['interface'])->getEndpoints()) ? 1 : 0,
            ];
        }

        return $optionsForOneService;
    }

    private function getEndpointsFromAnotherRegion(array $endpointsList, array $allRegions, string $service, int $serverId)
    {
        $optionsForOneService = [];
        $savedEndpoints = (new EndpointRepository())->getByServerId($serverId) ?: [];

        foreach ($allRegions as $region)
        {
            if (!empty($optionsForOneService))
            {
                return $optionsForOneService;
            }

            foreach ($endpointsList as $key => $value)
            {
                $optionsForOneService = array_merge($optionsForOneService, $this->addAvailableEndpointToArray($value, $region, $service, $savedEndpoints));
            }
        }

        return $optionsForOneService;
    }

    /**
     * @param Request $request
     * @param int $serverId
     */
    public function createOrUpdateServer(?array $data, int $serverId)
    {
        $serverModel = new Servers();
        $serverModel->createTableIfNotExist();

        $data[Servers::DOMAIN_NAME] = $data['domain'];

        $params = [ Servers::TENANT_ID, Servers::API_VERSION, Servers::DOMAIN_NAME, Servers::CERTIFICATE, Servers::PROJECT_NAME, Servers::PATH ];

        foreach ($params as $param)
        {
            if ($param == Servers::PATH && $data[$param])
            {
                $data[$param] = trim($data[$param], ' / ');
            }

            $serverModel->createOrUpdate($serverId, $param, null, $data[$param]);
        }
    }

    public function setEndpoints(int $serverId, array $data)
    {
        $params = WhmcsParamsHelper::getWhmcsParamsByServerId($serverId);
        $params['serverid'] = $serverId;

        $savedRegion = null;
        if (isset($params['region']) && !empty($params['region'])) {
            $savedRegion = $params['region'];
            unset($params['region']);
        }
        
        $savedIdentityRegion = Identity::getUseRegion();
        Identity::setUseRegion(null);

        try {
            $tenant = Factory::getTenantFromTestConnection($params);
            $tenantId = $tenant->getTenantID();
            $tenant->connect();
            
            if (!$tenantId) {
                return;
            }

            $endpoints = $tenant->getEndpointList();
            $normalized = (new EndpointNormalizer())->normalizeAndFilter($endpoints);
            
            (new EndpointRepository())->store($serverId, $normalized);
            $this->updateResources($serverId, $tenantId);
        } catch (\Exception $ex) {
            Logger::critical(LoggerMessages::EXCEPTION, [
                'server' => $serverId,
                'message' => $ex->getMessage(),
                'stacktrace' => $ex->getTraceAsString()
            ]);
            (new EndpointRepository())->storeEmpty($serverId);
        } finally {
            if ($savedRegion !== null) {
                $params['region'] = $savedRegion;
            }
            Identity::setUseRegion($savedIdentityRegion);
        }

    }

    /**
     * @param int $serverId
     * @param string $tenantId
     * @throws Exception
     */
    private function updateResources(int $serverId, string $tenantId)
    {
        $serverModel = new Servers();

        $serverModel->createOrUpdate($serverId, Servers::AVAILABLE_IMAGES, null, json_encode($this->getAvailableResources(new ImageModel($tenantId))));
        $serverModel->createOrUpdate($serverId, Servers::AVAILABLE_FLAVORS, null, json_encode($this->getAvailableResources(new FlavorModel($tenantId))));
        $serverModel->createOrUpdate($serverId, Servers::AVAILABLE_NETWORKS, null, json_encode($this->getAvailableResources(new NetworkModel($tenantId))));
        $serverModel->createOrUpdate($serverId, Servers::AVAILABLE_SECURITY_GROUPS, null, json_encode($this->getAvailableResources(new SecurityGroupModel($tenantId))));
    }

    /**
     * @param $model FlavorModel|ImageModel|NetworkModel|SecurityGroupModel
     * @return array
     * @throws Exception
     */
    private function getAvailableResources($model): array
    {
        $resources = $model->listSource();

        $parsedResources = [];

        foreach ($resources as $resource)
        {
            $parsedResources[] = [
                'UUID' => $resource['id'],
                'name' => $resource['name'] ?: $resource['id'],
            ];
        }

        return $parsedResources;
    }

    /**
     * @param int $serverId
     * @return array
     */
    public function getServerDetails(int $serverId)
    {
        return Servers::byServerID($serverId)->pluck('endpoint', 'service')->toArray();
    }


    /**
     * Update images, flavors and network lists
     * @param int $productId
     * @throws Exception
     */
    public function refreshResources(int $productId, int $serverId)
    {
        $tenant   = Factory::adminFromServerId($serverId);
        $tenant->setProductId($productId);
        $tenant->connect();
        $tenantId = $tenant->getTenantID();

        $this->updateResources($serverId, $tenantId);
    }

    public function refreshServerResources(?int $serverId)
    {
        $tenant   = Factory::adminFromServerId($serverId);
        $tenant->connect();
        $tenantId = $tenant->getTenantID();

        $this->updateResources($serverId, $tenantId);
    }

    /**
     * @param int $serverId
     */
    public function deleteByServerId(int $serverId)
    {
        Servers::byServerID($serverId)->delete();
    }
}
