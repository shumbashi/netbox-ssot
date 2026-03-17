<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Builders\ServiceUrlBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Config\ConfigResolver;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Factories\ServiceFactory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Helpers\UrlHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Compute;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Gnocchi;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Identity;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Image;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Metering;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Network;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Volume;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;

class Api
{
    private static $instance;
    private static string $endpointType = 'public';
    public $catalog = [];
    /**
     * @var Identity
     */
    public $identity;
    /**
     * @var Compute
     */
    public $compute;
    /**
     * @var Volume
     */
    public $volume;
    /**
     * @var Image
     */
    public $image;
    /**
     *
     * @var Metering
     */
    public $metering;
    /**
     *
     * @var Gnocchi
     */
    public $gnocchi;
    /**
     *
     * @var Network
     */
    public $network;
    /**
     * @var ComputeV1
     */
    public $tenantID;
    protected $useCatalog = 'admin';
    private $timeout = 60;
    private $serverID = 0;
    private $productID = 0;
    private $serviceID = 0;
    private $token;

    private $certificate;
    /**
     *
     * @var {\v3\Api\Identity
     */
    private $apIdentity;

    /**
     * Get API Instance
     *
     * @return Api
     */
    public static function getInstance($globalEndPoint = null, $tenantName = null, $tenantID = null, $username = null, $password = null, $apiVersion = null, $httpPrefix = null, $port = null, $serverID = null, $domainName = null, $certificate = null, $projectName = null, $productID = 0, $serviceId = 0, bool $debug = false)
    {

        if (empty(self::$instance))
        {
            self::$instance = new self();
        }

        if ($globalEndPoint)
        {
            self::$instance->connection($globalEndPoint, $tenantName, $tenantID, $username, $password, $httpPrefix, $apiVersion, $port, $domainName, $certificate, $projectName, $debug);
        }

        if (empty(self::$instance))
        {
            throw new OSException("Connection is undeclared", 0);
        }

        if (!is_null($serverID))
        {
            self::$instance->serverID  = $serverID;
            self::$instance->productID = $productID;
            self::$instance->serviceID = $serviceId;
        }

        return self::$instance;
    }

    /**
     * Get Token & set endpoints
     *
     * @param string $globalEndPoint
     * @param string $tenantName
     * @param string $tenantID
     * @param string $username
     * @param string $password
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function connection($globalEndPoint, $tenantName, $tenantID, $username, $password, $httpPrefix, $apiVersion = null, $port = null, $domainName = null, $certificate = null, $projectName = null, bool $debug = false)
    {
        $globalEndpointData = (new ServiceUrlBuilder($globalEndPoint))->getData();
        if (empty($globalEndpointData)) {
            throw new OSException('Cannot parse global endpoint URL:' . $globalEndPoint, 0);
        }

        $version = ($apiVersion) ?: $globalEndpointData['version'];

        $port = ($port) ? ':' . $port : '';

        $newEndPoint = UrlHelper::getDomain($globalEndPoint, $version);
        $this->apIdentity = new Identity($httpPrefix . $newEndPoint . $port . '/' . $version . (!empty($result['tenantID']) ? '/' . $result['tenantID'] : ''), null, [], $certificate, $debug);

        $result = $this->apIdentity->tokens($tenantName, $tenantID, $username, $password, $domainName, $certificate, $projectName);

        if (!isset($result['catalog']))
        {
            throw new \Exception('Unable to retrieve endpoint catalog. Please check server configuration.');
        }

        if (!isset($result['token']))
        {
            throw new \Exception('Unable to retrieve token. Please check server configuration.');
        }

        $this->catalog = $result['catalog'];
        $this->token = $result['token'];
        $this->tenantID = $tenantID;
        $this->certificate = $certificate;

        $this->identity = null;
        $this->compute  = null;
        $this->volume   = null;
        $this->image    = null;
        $this->metering = null;
        $this->gnocchi  = null;
        $this->network  = null;
    }

    static function setEndpointType($useEndPoint)
    {
        if (!in_array($useEndPoint, ["admin", "public"]))
        {
            throw new OpenStackApiException(sprintf("End point '%s' is not valid", $useEndPoint));
        }

        self::$endpointType = $useEndPoint;
    }


    public function compute(): Compute
    {
        return $this->createService('compute', func_get_args());
    }

    public function identity(): Identity
    {
        return $this->createService('identity', func_get_args());
    }

    public function image(): Image
    {
        return $this->createService('image', func_get_args());
    }

    public function metering(): Metering
    {
        return $this->createService('metering', func_get_args());
    }

    public function network(): Network
    {
        return $this->createService('network', func_get_args());
    }

    public function volume(): Volume
    {
        return $this->createService('volume', func_get_args());
    }

    public function metric(): Gnocchi
    {
        return $this->createService('gnocchi', func_get_args());
    }

    function createService($interface, $arguments)
    {
        $params = [];
        if ($arguments)
        {
            [$params] = $arguments;

            if (!is_array($params))
            {
                throw new OSException('First parameter have to be array', 2);
            }
        }

        if (!$this->apIdentity)
        {
            throw new \Exception(sprintf('Unable to create %s service, api connection not initialized', $interface));
        }

        if (empty($this->{$interface}))
        {
            $config = ConfigResolver::resolveConfig([
                'interface' => $interface,
                'serverId' => $this->serverID,
                'serviceId' => $this->serviceID,
                'productId' => $this->productID,
                'catalog' => $this->catalog,
                'token' => $this->token,
                'tenantId' => $this->tenantID,
                'endpointType' => static::$endpointType,
                'params' => $params,
                'allRegions' => $this->apIdentity->getRegions(),
                'usedRegion' => $this->apIdentity->getUseRegion(),
                'certificate' => $this->certificate
            ]);

            $this->{$interface} = ServiceFactory::factory($interface, $config);
        }

        return $this->{$interface};
    }

    public function getApIdentity()
    {
        return $this->apIdentity;
    }

    function timout($seconds)
    {
        $this->timeout = $seconds;
    }

    function getTimeOut()
    {
        return $this->timeout;
    }

    function useTenant($tenantID)
    {
        $this->tenantID = $tenantID;
    }
}
