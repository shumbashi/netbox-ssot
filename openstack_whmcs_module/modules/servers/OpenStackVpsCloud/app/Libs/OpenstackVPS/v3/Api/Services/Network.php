<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;

class Network extends AbstractService
{

    const USEENDPOINT = 'network';
    const VERSION     = 'v2.0';

    function listNetworks($tenantID = false)
    {
        $params = [];

        if ($tenantID)
        {
            $params['tenant_id'] = $tenantID;
        }

        $response = $this->client->get('networks', $params);

        $networks = [];
        foreach ($response['networks'] as $network)
        {
            $networks[$network['id']] = [
                'id'       => $network['id'],
                'name'     => $network['name'],
                'status'   => $network['status'],
                'ownerID'  => $network['tenant_id'],
                'external' => $network['router:external'],
                'subNets'  => []
            ];

            foreach ($network['subnets'] as $sub)
            {
                $networks[$network['id']]['subNets'][] = $sub;
            }
        }

        return $networks;
    }

    function getNetwork(string $networkId)
    {
        return $this->client->get(['networks', $networkId]);
    }

    function createRouter($name, $externalNetworkUUID = false)
    {
        $params = [
            'router' => [
                'name' => $name
            ]
        ];

        if ($externalNetworkUUID)
        {
            $params['router']['external_gateway_info'] = [
                'network_id' => $externalNetworkUUID
            ];
        }

        $response = $this->client->post(
            ['routers'],
            $params
        );

        return [
            'id' => $response['router']['id']
        ];
    }

    function listRouters($tenantID = false)
    {
        $params = [];

        if ($tenantID)
        {
            $params['tenant_id'] = $tenantID;
        }

        $response = $this->client->get('routers', $params);

        $routers = [];

        foreach ($response['routers'] as $router)
        {
            $routers[] = [
                'id'              => $router['id'],
                'name'            => $router['name'],
                'ownerID'         => $router['tenant_id'],
                'externalNetwork' => (!empty($router['external_gateway_info']['network_id'])) ? $router['external_gateway_info']['network_id'] : false
            ];
        }

        return $routers;
    }

    function createNetwork($name, $shared = false)
    {
        $response = $this->client->post('networks', [
            'network' => [
                'name'   => $name,
                'shared' => $shared
            ]
        ]);

        return [
            'id' => $response['network']['id']
        ];
    }

    function createSubNet($networkUUID, $cidr, $ipVersion = '4')
    {
        $response = $this->client->post('subnets', [
            'subnet' => [
                'network_id' => $networkUUID,
                'cidr'       => $cidr,
                'ip_version' => $ipVersion
            ]
        ]);

        return [
            'id' => $response['subnet']['id']
        ];
    }

    function addSubNetToRouter($routerID, $subNetID)
    {
        $this->client->put(['routers' => $routerID, 'add_router_interface'], ['subnet_id' => $subNetID]);
        return true;
    }

    function listFloatingIP($tenantID = null)
    {
        $params = [];

        if ($tenantID)
        {
            $params['tenant_id'] = $tenantID;
        }

        $response = $this->client->get('floatingips');

        $floatingIPs = [];
        foreach ($response['floatingips'] as $ip)
        {
            $floatingIPs[$ip['id']] = [
                'id'        => $ip['id'],
                'address'   => $ip['floating_ip_address'],
                'ownerID'   => $ip['tenant_id'],
                'networkID' => $ip['floating_network_id']
            ];
        }

        return $floatingIPs;
    }

    function getFloatingIps(array $query = [])
    {
        $floatingips = $this->client->get('floatingips', $query);
        return $floatingips['floatingips'];
    }

    function getFloatingIp(string $id, array $query = [])
    {
        $floatingip = $this->client->get(['floatingips', $id], $query);
        return $floatingip['floatingip'];
    }

    function createFloatingIp(array $query = [])
    {
        return $this->client->post(['floatingips'],
        [
            'floatingip' => $query
        ]);
    }

    function deleteFloatingIP($floatingId)
    {
        return $this->client->delete('floatingips/' . $floatingId);
    }

    public function updateFloatingIP(string $floatingIpId, array $params)
    {
        return $this->client->put(sprintf('floatingips/%s', $floatingIpId), [
               'floatingip' => (object)$params
            ]);
    }

    function listPorts(array $params = [])
    {
        $ports = $this->client->get('ports', $params);
        return $ports['ports'];
    }

    function listPortsKeyId($tenantID = null)
    {
        $params = [];

        if ($tenantID)
        {
            $params['tenant_id'] = $tenantID;
        }

        $response = $this->client->get('ports', $params);

        $output = [];

        foreach ($response['ports'] as $port)
        {
            $output[$port['id']] = [
                'id'         => $port['id'],
                'device_id'  => $port['device_id'],
                'network_id' => $port['network_id'],
                'subnets'    => []
            ];

            foreach ($port['fixed_ips'] as $fixed)
            {
                $output[$port['id']]['subnets'][] = $fixed['subnet_id'];
            }
        }

        return $output;
    }

    function updatePort(string $portID, array $params = [])
    {
        $response = $this->client->put('ports/' . $portID, [
            'port' => $params
        ]);

        return $response['port'];
    }

    function deletePort($portID)
    {
        return $this->client->delete('ports/' . $portID);
    }

    function getPort($portID)
    {
        $port = $this->client->get('ports/' . $portID);
        return $port['port'];
    }

    function createPort(array $port = [])
    {
        $port = $this->client->post('ports', ['port' => $port]);
        return $port['port'];
    }

    function getQuota($tenantID)
    {
        $response = $this->client->get(['quotas' => $tenantID]);
        return [
            'subnet'     => $response['quota']['subnet'],
            'network'    => $response['quota']['network'],
            'floatingip' => $response['quota']['floatingip'],
            'router'     => $response['quota']['router'],
            'port'       => $response['quota']['port']
        ];
    }

    function setQuota($tenantID, $data)
    {
        $this->client->put([
            'quotas' => $tenantID
        ], [
            'quota' => [
                'subnet'     => $data['subnet'],
                'network'    => $data['network'],
                'floatingip' => $data['floating_ips'],
                'router'     => $data['router'],
                'port'       => $data['port']
                //  ,'fixedip'              => $data['fixed_ips']
            ]
        ]);

        return true;
    }

    function listSubnetsKeyById($tenantID = false)
    {
        $params = [];

        if ($tenantID)
        {
            $params['tenant_id'] = $tenantID;
        }

        $response = $this->client->get('subnets', $params);

        $output = [];

        foreach ($response['subnets'] as $subnet)
        {
            $output[$subnet['id']] = [
                'UUID' => $subnet['id'],
                'name' => $subnet['name'],
                'cidr' => $subnet['cidr']
            ];
        }

        return $output;
    }

    function listSubnets(array $params = [])
    {
        $response = $this->client->get('subnets', $params);

        if (!is_array($response) || !isset($response['subnets'])) {
            return [];
        }

        return $response['subnets'];
    }

    /*
     * Security Groups
     */

    public function getSecurityGroup($groupID)
    {
        return $this->client->get(['security-groups' => $groupID]);
    }

    public function createSecurityGroup($groupName, $tenantID = null)
    {
        return $this->client->post('security-groups', [
            'security_group' => [
                'name'      => $groupName,
                'tenant_id' => $tenantID,
                //                        'project_id' => $tenantID
            ]
        ]);
    }

    public function listSecurityGroups(array $query = [])
    {
        return $this->client->get('security-groups', $query)['security_groups'] ?: [];
    }

    public function getSecurityRules($groupID)
    {
        return $this->client->get('security-groups/' . $groupID);
    }

    public function deleteSecurityGroup($groupID)
    {
        return $this->client->delete('security-groups/' . $groupID);
    }

    public function removeSecurityRule($ruleID)
    {
        return $this->client->delete('security-group-rules/' . $ruleID);
    }

    public function addNewSecurityRule($data)
    {
        return $this->client->post('security-group-rules', [
            'security_group_rule' => $data
        ]);
    }

}
