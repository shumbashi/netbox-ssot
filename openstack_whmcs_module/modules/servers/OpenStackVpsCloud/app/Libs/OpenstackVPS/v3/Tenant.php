<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3;

use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\Model;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services\Identity;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use WHMCS\Database\Capsule as DB;

class Tenant extends Model
{
    const ITEM_ROUTERS = 'routers';
    const ITEM_PORTS   = 'ports';


    private $_adminTenantID, $_endPoint, $_userName, $_password;

    /**
     * @author Michal Czech <michael@modulesgarden.com>
     * @var array of OpenStackVPS objects
     */
    private $_VPSList = [];

    /**
     *
     * @var QuotaAvaiable
     */
    private $_resourceAvaiable;

    /**
     *
     * @var ResourcesUsed
     */
    private $_resourceUsed;

    /**
     *
     * @var OpenStackFlavor[]
     */
    private $_flavorList;

    /**
     *
     * @var OpenStackImage[]
     */
    private $_imageList;

    /**
     *
     * @var OpenStackNetwork[]
     */
    private $_networkList;

    /**
     *
     * @var FloatingIP[]
     */
    private $_floatingIPList;

    /**
     *
     * @var Role[]
     */
    private $_roleList;

    /**
     *
     * @var SecurityGroups[]
     */
    private $_securityGroupList = [];

    /**
     *
     * @var KeyPair[]
     */
    private $_keyPairList = [];

    /**
     *
     * @var Meter[]
     */
    private $_meterList = [];

    /**
     *
     * @var BlockDevice[]
     */
    private $_blockDeviceList = [];

    /**
     *
     * @var ApiKeystone
     */
    private $_apiKeystone;

    /*
     * Service port
     */
    private $servicePort;

    /*
     * Server ID
     */
    private $serverID;

    /*
     * Api Version
     */
    private $apiVersion;


    /*
     * Http Prefix
     */
    private $httpPrefix;

    /*
     * Domain Name
     */
    private $domainName;

    /*
     * Certificate
     */
    private $certificate;

    /*
     * Certificate
     */
    private $projectName;

    private $productID;

    private $serviceId;

    /**
     * @author Michal Czech <michael@modulesgarden.com>
     * Construct void
     */
    function __construct($endPoint, $defaultTenantID, $username, $password, $apiVersion, $httpPrefix, $port = null, $serverID = null, $domainName = "Default", $certificate = "", $projectName = "", $productID = 0, $serviceId = 0)
    {
        $this->_endPoint      = $endPoint;
        $this->_adminTenantID = $this->tenantID = $defaultTenantID;
        $this->_userName      = $username;
        $this->_password      = $password;
        $this->servicePort    = $port;
        $this->serverID       = $serverID;
        $this->apiVersion     = $apiVersion;
        $this->httpPrefix     = $httpPrefix;
        $this->domainName     = $domainName;
        $this->certificate    = $certificate;
        $this->projectName    = $projectName;
        $this->productID      = $productID;
        $this->serviceId      = $serviceId;
    }

    /**
     *
     * @param type $params
     * @return Tenant
     * @throws Exception
     */
    static function WHMCSFactory($params, $loginAsAdmin = false, $tenantID = false)
    {
        [$params['serverhostname'], $endpoint_type] = explode('|', $params['serverhostname'], 2);

        if (!empty($endpoint_type))
        {
            Api::setEndpointType($endpoint_type);
        }

        if (isset($params['serverhostname']) && $params['serverhostname'])
        {
            $endPoint = $params['serverhostname'];
        }
        elseif (isset($params['serverip']) && $params['serverip'] && $params['tenantId'])
        {
            $endPoint = $params['serverip'];
        }
        else
        {
            throw new OSException('WHMCS Server for OpenStack is not configured properly.');
        }

        if (!empty($params['path']) && !is_numeric($params['path']))
        {
            $endPoint .= '/' . trim($params['path'], '/');
        }

        if ($params['moduletype'] == 'OpenStackCloud' && $loginAsAdmin == false)
        {
            $userName = $params['username'];
            $password = $params['password'];
        }
        else
        {
            if ($params['moduletype'] == 'RackspaceCloudOpenStack')
            {

                $userName = $params['serverusername'];
                $password = $params['serverpassword'];
                Api::setEndpointType('public');
            }
            else
            {
                $userName = $params['serverusername'];
                $password = $params['serverpassword'];
            }
        }

        if (isset($params['serversecure']) && $params['serversecure'] == "on")
        {
            $httpPrefix = 'https://';
        }
        else
        {
            $httpPrefix = 'http://';
        }

        if (!empty($params['domain']))
        {
            $domainName = $params['domain'];
        }
        else
        {
            $domainName = "Default";
        }

        if (!empty($params['certificate']))
        {
            $certificate = $params['certificate'];
        }
        else
        {
            $certificate = "";
        }
        if (!empty($params['projectName']))
        {
            $projectName = $params['projectName'];
        }
        else
        {
            $projectName = "";
        }

        if (!empty($params['certificate']))
        {
            $port = null;
        }
        else
        {
            $port = $params['serverport'];
            $port = is_numeric($params['path']) ? $params['path'] : $port;
        }

        $obj = new self(
            $endPoint, $params['tenantId'], $userName, $password, $params['apiVersion'], $httpPrefix, $port, $params['serverid'], $domainName, $certificate, $projectName, $params['pid'] ?: 0, $params['serviceid'] ?: 0
        );

        if (isset($params['customfields']['tenantID']) && $params['customfields']['tenantID'] && $loginAsAdmin === false)
        {
            $obj->setTenant($params['customfields']['tenantID']);
        }

        if ($tenantID && $loginAsAdmin === false)
        {
            $obj->setTenant($tenantID);
        }

        $obj->setTenant($tenantID);

        if (isset($params['region']) && !empty($params['region']))
        {
            Identity::setUseRegion($params['region']);
        }

        $obj->connect();
        if (isset($params['customfields']['tenantID']) && $params['customfields']['tenantID'] && $loginAsAdmin === true)
        {
            $obj->setTenant($params['customfields']['tenantID']);
        }

        return $obj;
    }

    /**
     * Set tenant ID
     *
     * @param string $id
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function setTenant($id)
    {
        $this->tenantID = $id;
    }

    /**
     * Connect & authorize API
     *
     * @param string $endPoint full url
     * @param string $tenantName
     * @param string $username
     * @param string $password
     * @throws OSException
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function connect()
    {
        Api::getInstance(
            $this->_endPoint, null, $this->tenantID, $this->_userName, $this->_password, $this->apiVersion, $this->httpPrefix, $this->servicePort, $this->serverID, $this->domainName, $this->certificate, $this->projectName, $this->productID, $this->serviceId
        );
    }

    function getEndpointList()
    {
        $list    = Api::getInstance()->catalog;
        $newList = [];
        foreach ($list as $key => $value)
        {

            foreach ($value['endpoints'] as $enpoint)
            {
                $newList[] = [
                    'id'        => $enpoint['id'],
                    'service'   => ucfirst($value['type']),
                    'region'    => $enpoint['region'],
                    'interface' => $enpoint['interface'],
                    'url'       => $enpoint['url']
                ];
            }
        }

        return $newList;
    }

    function VPS($UUID = 'NEW', $reload = false)
    {
        if (empty($this->tenantID))
        {
            return [];
        }

        if ($reload)
        {
            $this->_VPSList = [];
        }

        $prototype = new VPSModel($this->tenantID, $UUID != 'NEW' ? $UUID : null, []);

        if (empty($this->_VPSList))
        {
            $listData = $prototype->listSource();
            if (!is_array($listData))
            {
                throw new OSException("Data from OpenStackModelVPS::listSource is not array", 0);
            }

            foreach ($listData as $details)
            {
                if ($UUID != 'NEW' && $details['id'] == $UUID)
                {
//                    $this->_VPSList[$details['id']] = new Models\VPS($this->tenantID, $details['id'], $details);
                    $this->_VPSList[$details['id']] = new VPSModel($this->tenantID, $details['id'], $details);
                }
            }
        }

        if ($UUID == 'NEW' && empty($this->_VPSList['NEW']))
        {
            $this->_VPSList['NEW'] = new VPSModel($this->tenantID);
        }

        if (empty($this->_VPSList[$UUID]))
        {
            throw new OSException("Cannot find element:$UUID on VPS list", 1404);
        }

        return $this->_VPSList[$UUID];
    }

    /**
     * Access to Tenant properties
     *
     * @return mixed
     * @throws OSException
     * @author Michal Czech <michael@modulesgarden.com>
     * @method Models\VPS[] listVPSs(boolean $reload = false)
     * @method Role[] listRoles(boolean $reload = false)
     * @method Role role(string $UUID = 'NEW', boolean $reload = false)
     * @method Flavor[] listFlavors(boolean $reload = false)
     * @method Flavor flavor(string $UUID = 'NEW', boolean $reload = false)
     * @method Network[] listNetworks(boolean $reload = false, boolean $onlyMyNetworks = false)
     * @method Network network(string $UUID = 'NEW', boolean $reload = false)
     * @method Image[] listImages(boolean $reload = false)
     * @method Image image(string $UUID = 'NEW', boolean $reload = false)
     * @method FloatingIP[] listFloatingIPs(boolean $reload = false, boolean $onlyMyNetworks = false)
     * @method OPenStackModelFloatingIP floatingIP(string $UUID = 'NEW', boolean $reload = false)
     * @method SecurityGroups[] listSecurityGroups(boolean $reload = false)
     * @method SecurityGroups securityGroup(string $UUID = 'NEW', boolean $reload = false)
     * @method KeyPair[] listKeyPairs(boolean $reload = false)
     * @method KeyPair keyPair(string $UUID = 'NEW', boolean $reload = false)
     * @method BlockDevice[] listBlockDevices(boolean $reload = false)
     * @method BlockDevice blockDevice(string $UUID = 'NEW', boolean $reload = false)
     */
    function __call($name, $arguments)
    {
        if (strpos($name, 'list') === 0)
        {
            $type   = substr($name, 4, strlen($name) - 5);
            $reload = !empty($arguments[0]);
            $onlyMy = !empty($arguments[1]);
            $all    = true;
            $UUID   = false;
            $params = empty($arguments[2]) ? [] : $arguments[2];
        }
        else
        {
            $type = $name;

            if (isset($arguments[0]))
            {
                $UUID = $arguments[0];
            }
            else
            {
                if (count($arguments) > 0)
                {
                    $UUID = $arguments[0];
                }
                else
                {
                    $UUID = 'NEW';
                }
            }

            $reload = !empty($arguments[1]);
            $all    = false;
            $onlyMy = false;
            $params = empty($arguments[2]) ? [] : $arguments[2];
        }

        switch ($type)
        {
            case 'VPS':
                break;
            case 'floatingIP':
            case 'FloatingIP':
                $type = 'floatingIP';
                break;
            case 'SecurityGroup':
            case 'securityGroup':
                $type = 'securityGroup';
                break;
            case 'keyPair':
            case 'KeyPair':
                $type = 'keyPair';
                break;
            case 'BlockDevice':
            case 'blockDevice':
                $type = 'blockDevice';
                break;
            default:
                $type = strtolower($type);
        }


        $propertyName = '_' . $type . 'List';
        if (!property_exists($this, $propertyName))
        {
            throw new OSException('Property for type: ' . $type . ' not exist', 0);
        }


        if (empty($this->tenantID))
        {
            return [];
        }

        if ($reload)
        {
            $this->{$propertyName} = [];
        }

        $class = '\\ModulesGarden\\OpenStackVpsCloud\\App\\Libs\\Services\\VirtualMachine\\Models\\' . ucfirst($type . 'Model');

        $prototype = new $class($this->tenantID, null, $params);

        if (!method_exists($prototype, 'listSource'))
        {
            throw new OSException("Can't find method 'listSource' in class:$class", 0);
        }

        if (empty($this->{$propertyName}))
        {
            $listData = $prototype->listSource();
            if (!is_array($listData))
            {
                throw new OSException("Data from $class::listSource is not array", 0);
            }

            foreach ($listData as $details)
            {
                try {
                    $object = new $class($this->tenantID, $details['id'], $details);
                    $this->{$propertyName}[$details['id']] = $object;
                } catch (\Exception $ex) {}
            }
        }

        if ($UUID == 'NEW' && empty($this->{$propertyName}['NEW']))
        {
            $this->{$propertyName}['NEW'] = new $class($this->tenantID);
        }

        if ($all)
        {
            if ($onlyMy && property_exists(reset($this->{$propertyName}), 'ownerID'))
            {
                $output = $this->{$propertyName};
                foreach ($output as &$element)
                {
                    if ($element->ownerID !== $this->tenantID)
                    {
                        $element = null;
                    }
                }

                return array_filter($output);
            }
            else
            {
                return $this->{$propertyName};
            }
        }

        if (empty($this->{$propertyName}[$UUID]))
        {
            throw new OSException("Cannot find element:$UUID on $type list", 1404);
        }

        return $this->{$propertyName}[$UUID];
    }


    function testEndPoints()
    {
        $endPointToTest = ['compute', 'identity', 'image', 'network', 'volume', 'metric' ];
        $results        = [];

        //TODO: This is causing common php timeouts, separate this into multiple dropdowns testing single endpoint
        foreach ($endPointToTest as $name)
        {
            try
            {
                $apiInstance = Api::getInstance();
                $apiInstance->timout(10);

                $endpointObject = $apiInstance->{$name}();
                $endpointObject->testEndpoint();

                $results[$name] = true;
            }
            catch (Exception $e)
            {
                $results[$name] = false;

                \logModuleCall(
                    ModuleConstants::getModuleName(),
                    sprintf('test.public.endpoint.%s', $name),
                    [],
                    [
                        'message' => $e->getMessage()
                    ],
                    0,
                    0
                );
            }
        }

        return $results;
    }

    public function getApiVersion()
    {
        return Api::getInstance()->getApIdentity()->getVersion();
    }

    /**
     *
     * @return Api
     */
    public function api()
    {
        return Api::getInstance();
    }

    /**
     * @return mixed
     */
    public function getTenantID()
    {
        return $this->tenantID;
    }

    public function setProductId($productId)
    {
        $this->productID = $productId;
    }


}
