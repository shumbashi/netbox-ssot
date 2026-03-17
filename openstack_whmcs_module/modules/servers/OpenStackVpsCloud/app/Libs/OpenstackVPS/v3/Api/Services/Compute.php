<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\UUID;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Services\Logs;
use stdClass;

class Compute extends AbstractService
{

    const USEENDPOINT = 'compute';
    const VERSION = '';

    public function getPassword(string $id)
    {
        return $this->client->get([
            'servers' => $id,
            'os-server-password'
        ], [
        ]);
    }

    function getExtension()
    {
        $response = $this->client->get('extensions');
        $extensions = [];

        foreach ($response['extensions']['values'] as $extension) {
            $extensions[] = [
                'name' => $extension['name'],
                'alias' => $extension['alias']
            ];
            $this->_extensions[$extension['alias']] = $extension['name'];
        }

        return $extensions;
    }

    /**
     * Get Tenant Quota Values
     *
     * @return array
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function getQuotas($tenantID)
    {
        $response = $this->client->get([
            'os-quota-sets' => $tenantID
        ]);

        $output = [];
        foreach ($response['quota_set'] as $key => $value) {
            $output[$key] = $value;
        }
        return $output;
    }

    /**
     * Set Tenant Quotas
     *
     * @param string $tenantID
     * @param array $values
     * @return boolean
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function setQuotas($tenantID, $values)
    {
        $this->client->put(
            [
                'os-quota-sets' => $tenantID
            ],
            [
                'quota_set' => [
                    'cores' => $values['cores'],
                    'fixed_ips' => $values['fixed_ips'],
                    'floating_ips' => $values['floating_ips'],
                    //   ,'injected_file_content_bytes'  => $values['injected_file_content_bytes']
                    //   ,'injected_file_path_bytes'     => $values['injected_file_path_bytes']
                    //   ,'injected_files'               => $values['injected_files'],
                    'instances' => $values['instances'],
                    //  ,'key_pairs'                    => $values['key_pairs']
                    // ,'metadata_items'               => $values['metadata_items'],
                    'ram' => $values['ram'],
                    //  ,'security_group_rules'         => $values['security_group_rules']
                    //  ,'security_groups'              => $values['security_groups']
                ]
            ]
        );

        return true;
    }

    function getLimits()
    {
        $limits = $this->client->get('limits');
        $absolute = $limits['limits']['absolute'];
        return [
            'used' => [
                'vcpus' => $absolute['totalCoresUsed'],
                'securityGroups' => $absolute['totalSecurityGroupsUsed'],
                'floatingIPs' => 'lying',
                'instances' => $absolute['totalInstancesUsed'],
                'ram' => $absolute['totalRAMUsed']
            ],
            'other' => 'TODO or use getQuotas'
        ];
    }

    /**
     * Get List of VPS in current Tenant
     *
     * @return array [id,name]
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function VPSList()
    {
        $return = $this->client->get('servers');

        $output = [];
        foreach ($return['servers'] as $srv) {
            $output[] = [
                'id' => $srv['id'],
                'name' => $srv['name']
            ];
        }

        return $output;
    }

    /**
     * Get List of VPS in current Tenant
     *
     * @return array [id,name,status,stateTask]
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function VPSDetailedList()
    {
        $return = $this->client->get(['servers' => 'detail']);

        return $this->parseVPSDetails($return['servers']);
    }

    private function parseVPSDetails($return)
    {
        $output = [];
        foreach ($return as $srv) {
            //Provide THE SAME ARRAY FORMAT AS BELOW
            $output[$srv['id']] = [
                'id' => $srv['id'],
                'name' => $srv['name'],
                'status' => $srv['status'],
                'dateCreated' => $srv['created'],
                'stateTask' => $srv['OS-EXT-STS:task_state'],
                'addresses' => [],
                'securityGroups' => [],
                'flavorID' => $srv['flavor']['id'],
                'imageID' => isset($srv['image']['id']) ? $srv['image']['id'] : false,
                'keyName' => $srv['key_name'],
                'blockDevicesList' => [],
                'customScript' => $srv['OS-EXT-SRV-ATTR:user_data'],
                'metadata' => $srv['metadata'],
            ];

            foreach ($srv['os-extended-volumes:volumes_attached'] as $blockDevice) {
                $output[$srv['id']]['blockDevicesList'][] = $blockDevice['id'];
            }

            foreach ($srv['addresses'] as $netoworkName => $network) {
                foreach ($network as $interface) {
                    $output[$srv['id']]['addresses'][] = [
                        'mac' => $interface['OS-EXT-IPS-MAC:mac_addr'],
                        'version' => $interface['version'],
                        'addr' => $interface['addr'],
                        'type' => $interface['OS-EXT-IPS:type'],
                        'networkName' => $netoworkName
                    ];
                }
            }

            foreach ((array)$srv['security_groups'] as $group) {
                $output[$srv['id']]['securityGroups'][] = $group['name'];
            }

            if (isset($srv['fault'])) {
                $output['error'] = [
                    'message' => $srv['fault']['message'],
                    'code' => $srv['fault']['code']
                ];
            }
        }

        return $output;
    }

    /**
     * Get Details for specific VPS
     *
     * @param UUID $id
     * @return array [id,name,status,stateTask]
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function getVPSDetails($id)
    {
        $return = $this->client->get(['servers' => $id], [],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.6',
                'X-OpenStack-Nova-API-Version' => '2.6',
            ]);

        $output = $this->parseVPSDetails([$return['server']]);

        if (!empty($return['server']['fault'])) {
            foreach ($output as &$srv) {
                $srv['error'] = $return['server']['fault']['message'];
            }
        }

        return reset($output);
    }

    /**
     * Start VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function startVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'os-start' => null
        ]);

        return true;
    }

    /**
     * Stop VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function stopVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'os-stop' => null
        ]);

        return true;
    }

    /**
     * Pause VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function pauseVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'pause' => null
        ]);

        return true;
    }

    /**
     * Unpause VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function unpauseVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'unpause' => null
        ]);

        return true;
    }

    /**
     * Suspend VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function suspendVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'suspend' => null
        ]);

        return true;
    }

    /**
     * Resume VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function resumeVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'resume' => null
        ]);

        return true;
    }

    /**
     * Lock VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function lockVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'lock' => null
        ]);

        return true;
    }

    /**
     * Unlock VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function unlockVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'unlock' => null
        ]);

        return true;
    }

    /**
     * Create Backup VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function createBackup($id, $name, $type = 'daily', $rotation = 1)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'createBackup' => [
                'name' => $name,
                'backup_type' => $type,
                'rotation' => $rotation
            ]
        ]);

        return true;
    }

    /**
     * Get Console VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function getVNCConsole($id, $type = 'novnc')
    {
        $response = $this->client->post([
            'servers' => $id,
            'remote-consoles'
        ], [
            'remote_console' => [
                'protocol' => 'vnc',
                'type' => $type
            ]
        ],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.6',
                'X-OpenStack-Nova-API-Version' => '2.6',
            ]);


        return $response['remote_console']['url'] . '&title=(' . $id . ')';
    }

    /**
     * @param $id
     * @param string $type
     * @return string
     * @throws OpenStackApiException
     */
    function getSPICEConsole($id, $type = 'spice-html5')
    {
        $response = $this->client->post([
            'servers' => $id,
            'remote-consoles'
        ], [
            'remote_console' => [
                'protocol' => 'spice',
                'type' => $type
            ]
        ],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.6',
                'X-OpenStack-Nova-API-Version' => '2.6',
            ]);


        return $response['remote_console']['url'] . '&title=(' . $id . ')';
    }

    /**
     * @param $id
     * @param string $type
     * @return string
     * @throws OpenStackApiException
     */
    function getRDPConsole($id, $type = 'rdp-html5')
    {
        $response = $this->client->post([
            'servers' => $id,
            'remote-consoles'
        ], [
            'remote_console' => [
                'protocol' => 'rdp',
                'type' => $type
            ]
        ],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.6',
                'X-OpenStack-Nova-API-Version' => '2.6',
            ]);

        return $response['remote_console']['url'] . '&title=(' . $id . ')';
    }

    /**
     * @param $id
     * @param string $type
     * @return string
     * @throws OpenStackApiException
     */
    function getSerialConsole($id, $type = 'serial')
    {
        $response = $this->client->post([
            'servers' => $id,
            'remote-consoles'
        ], [
            'remote_console' => [
                'protocol' => 'serial',
                'type' => $type
            ]
        ],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.6',
                'X-OpenStack-Nova-API-Version' => '2.6',
            ]);

        return $response['remote_console']['url'] . '&title=(' . $id . ')';
    }

    /**
     * Delete VPS
     *
     * @param string $UUID
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function delete($UUID)
    {
        $this->client->delete(['servers' => $UUID]);

        return true;
    }

    /**
     * Force Delete VPS
     *
     * @param string $id UUID
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function forceDelete($UUID)
    {
        $this->client->post([
            'servers' => $UUID,
            'action'
        ], [
            'forceDelete' => null
        ]);

        return true;
    }

    /**
     * Restores a deleted VPS.
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function restore($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'restore' => null
        ]);

        return true;
    }

    /**
     * Gets basic usage data for a specified VPS.
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function getDiagnostics($id)
    {
        $response = $this->client->get([
            'servers' => $id,
            'diagnostics'
        ]);

        $output = [];

        foreach ($response as $key => $name) {
            $output[$key] = $name;
        }

        return $output;
    }

    /**
     * Create Private Flavor
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function createFlavor($name, $params, $public = false)
    {
        $config = [
            'name' => $name,
            'disk' => $params['disk'],
            'ram' => $params['ram'],
            'vcpus' => $params['vcpus'],
            'os-flavor-access:is_public' => ($public) ? true : false
        ];

        if (isset($params['rxtx_factor'])) {
            $config['rxtx_factor'] = $params['rxtx_factor'];
        }

        $response = $this->client->post('flavors', ['flavor' => $config]);

        return $response['flavor']['id'];
    }

    function createExtraSpecsForFlavor($id, $specs = [])
    {
        $URL = [
            'flavors' => $id,
            'os-extra_specs'
        ];

        $response = $this->client->post($URL, ['extra_specs' => $specs]);

        return $response;
    }

    /**
     * List Flavors
     *
     * @return array [id,name]
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function listFlavors()
    {
        $response = $this->client->get('flavors');
        $flavors = [];

        foreach ($response['flavors'] as $flavor) {
            $flavors[$flavor['id']] = [
                'id' => $flavor['id'],
                'name' => $flavor['name']
            ];
        }

        return $flavors;
    }

    function getFlavor($id)
    {
        $response = $this->client->get(['flavors' => $id]);

        return [
            'name' => $response['flavor']['name'],
            'disk' => $response['flavor']['disk'],
            'ram' => $response['flavor']['ram'],
            'vcpus' => $response['flavor']['vcpus'],
            'public' => !empty($response['flavor']['os-flavor-access:is_public']) ? true : false,
            'rxtxFactor' => $response['flavor']['rxtx_factor'],
            'swap' => $response['flavor']['swap']
        ];
    }

    /**
     * Puts a VPS in rescue mode. Changes status to RESCUE.
     *
     * @param UUID $id
     * @param string $password
     * @return boolean
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function rescueVPS($id, $password, $rescueImageRef)
    {
        $URL = [
            'servers' => $id,
            'action'
        ];
        $data = [
            'rescue' => [
                'adminPass' => $password
            ]
        ];

        if ($rescueImageRef !== "default") {
            $data['rescue']['rescue_image_ref'] = $rescueImageRef;
        }

        return $this->client->post($URL, $data,
            '',
            [
                'OpenStack-API-Version' => 'compute 2.87',
            ]);
    }

    /**
     * Unrescues a VPS
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function unrescueVPS($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'unrescue' => null
        ]);

        return true;
    }

    /**
     * Change VPS admin password
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function changePassword($id, $password)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'changePassword' => [
                'adminPass' => $password
            ]
        ]);

        return true;
    }

    /**
     * Reboot VPS
     *
     * @param UUID $id
     * @param string $type SOFT,HARD
     * @return boolean
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function rebootVPS($id, $type = 'SOFT')
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'reboot' => [
                'type' => $type
            ]
        ]);

        return true;
    }

    /**
     * Create VPS
     *
     * @param string $name
     * @param string $flavorRef
     * @param string $imageRef
     * @return array [id,password]
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function create($name, $flavor, $image, $networks = [], $user_data = null, $keyPair = null, $securityGroups = [], $blockDevices = [], $password = null, $metadata = null, $availability_zone = null)
    {
        $config = [
            'name' => $name,
            'flavorRef' => $flavor,
            'networks' => $networks
        ];

        if ($image) {
            $config['imageRef'] = $image;
        }

        if ($password) {
            $config['adminPass'] = $password;
        }
        if ($user_data) {
            $config['user_data'] = $user_data;
        }
        if ($blockDevices) {
            $config['block_device_mapping_v2'] = $blockDevices;
        }
        if ($metadata && is_array($metadata)) {
            $config['metadata'] = $metadata;
        }
        if ($securityGroups) {
            $config['security_groups'] = $securityGroups;
        }

        if ($keyPair) {
            $config['key_name'] = $keyPair;
        }

        if ($availability_zone) {
            $config['availability_zone'] = $availability_zone;
        }

        $response = $this->client->post([
            'servers'
        ], [
            'server' => $config
        ],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.6',
                'X-OpenStack-Nova-API-Version' => '2.6',
            ]);

        return [
            'password' => $response['server']['adminPass'],
            'id' => $response['server']['id']
        ];
    }

    /**
     * Rebuild VPS
     *
     * @param UUID $id
     * @param string $name
     * @param string $adminPass
     * @param string $imageRef
     * @param string $userData
     * @return boolean
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function rebuild($id, $name, $adminPass, $imageRef, $userData)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'rebuild' => [
                'name' => $name,
                'imageRef' => $imageRef,
                'adminPass' => $adminPass,
                'user_data' => base64_encode($userData)
            ],
        ],
            '',
            [
                'OpenStack-API-Version' => 'compute 2.88'
            ]);

        return true;
    }

    /**
     * Resize VPS
     *
     * @param UUID $id
     * @param string $flavorRef
     * @return boolean
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function resize($id, $flavorRef)
    {
        $resp = $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'resize' => [
                'flavorRef' => $flavorRef
            ]
        ]);
        return true;
    }

    /**
     * Confirms a pending resize action.
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function confirmResize($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'confirmResize' => null
        ]);
    }

    /**
     * Cancels and reverts a pending resize action.
     *
     * @param UUID $id
     * @return boolean
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    function revertResize($id)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'revertResize' => null
        ]);

        return true;
    }

    function createInterface($VPSID, $portID)
    {
        $response = $this->client->post([
            'servers' => $VPSID,
            'os-interface'
        ], [
            'interfaceAttachment' => [
                'port_id' => $portID
            ]
        ]);

        return [
            'id' => $response['interfaceAttachment']['port_id'],
            'address' => $response['interfaceAttachment']['fixed_ips'][0]['ip_address']
        ];
    }


    function deleteInterface($VPSID, $interfaceID)
    {
        return $this->client->delete([
            'servers' => $VPSID,
            'os-interface' => $interfaceID
        ]);
    }

    function listInterfaces(string $instanceId) {
        return $this->client->get([
            'servers' => $instanceId,
            'os-interface'
        ]);
    }

    //TODO: remove
    function listInterface($VPSID)
    {
        $response = $this->client->get([
            'servers' => $VPSID,
            'os-interface'
        ]);

        $output = [];
        foreach ($response['interfaceAttachments'] as $interface)
        {
            foreach ($interface['fixed_ips'] as $k => $fixedIp)
            {
                $output[] = [
                    'portID'      => $interface['port_id'],
                    'netID'       => $interface['net_id'],
                    'state'       => $interface['port_state'],
                    'fixedIP'     => $fixedIp['ip_address'],
                    'subnetID'    => $fixedIp['subnet_id'],
                    'fixedSubNet' => $fixedIp['subnet_id'],
                    'mac'         => $interface['mac_addr']
                ];
            }
        }

        return $output;
    }

    function getTenantUsage($id, $from = 'now', $to = 'now')
    {
        $params = [];

        if ($from == 'now')
        {
            $params['start'] = gmdate('Y-m-d', (strtotime('now') - 180)) . 'T' . gmdate('h:m:s', (strtotime('now') - 180));
        }
        else
        {
            $params['start'] = gmdate('Y-m-d', (strtotime('now') - 180)) . 'T' . gmdate('h:m:s', (strtotime('now') - 180));
        }

        if ($to == 'now')
        {
            $params['stop'] = gmdate('Y-m-d') . 'T' . gmdate('h:m:s');
        }
        else
        {
            $params['stop'] = gmdate('Y-m-d', strtotime($to)) . 'T' . gmdate('h:m:s', strtotime($to));
        }

        $data = $this->client->get(
            ['os-simple-tenant-usage' => $id],
            $params
        );

        $usage = [
            'memory'    => 0,
            'vcpus'     => 0,
            'diskGB'    => 0,
            'hours'     => 0,
            'instances' => 0
        ];

        foreach ($data['tenant_usage']['server_usages'] as $srv)
        {
            $usage['memory'] += $srv['memory_mb'];
            $usage['vcpus']  += $srv['vcpus'];
            $usage['diskGB'] += $srv['local_gb'];
            $usage['hours']  += $srv['hours'];
            $usage['instances']++;
        }

        return $usage;
    }

    function deleteFlavor($UUID)
    {
        $this->client->delete(['flavors' => $UUID]);
    }

    function updateFlavor($UUID, $params)
    {
        $config = [
            'disk'  => $params['disk'],
            'ram'   => $params['ram'],
            'vcpus' => $params['vcpus']
        ];

        if (isset($params['rxtx_factor']))
        {
            $config['rxtx_factor'] = $params['rxtx_factor'];
        }

        $response = $this->client->put(['flavors' => $UUID], ['flavor' => $config]);

        return $response['flavor']['id'];
    }

    function listKeyPairs()
    {
        $response = $this->client->get('os-keypairs');

        $keypairs = [];

        foreach ($response['keypairs'] as $pair)
        {
            $keypairs[] = [
                'id'          => $pair['keypair']['name'],
                'name'        => $pair['keypair']['name'],
                'public'      => $pair['keypair']['public_key'],
                'fingerPrint' => $pair['keypair']['fingerprint']
            ];
        }

        return $keypairs;
    }

    function createKeyPair($name, $public = null)
    {
        $data = ['name' => $name];

        if ($public !== null)
        {
            $data['public_key'] = $public;
        }

        $response = $this->client->post('os-keypairs',
            ['keypair' => $data]
        );

        return [
            'public'      => $response['keypair']['public_key'],
            'private'     => $response['keypair']['private_key'],
            'fingerPrint' => $response['keypair']['fingerprint']
        ];
    }

    function deleteKeyPair($name)
    {
        $this->client->delete(['os-keypairs' => $name]);
        return true;
    }

    function getKeyPair($name)
    {
        $data = $this->client->get(['os-keypairs' => $name]);
        return [
            'id'          => $data['keypair']['name'],
            'name'        => $data['keypair']['name'],
            'public'      => $data['keypair']['public_key'],
            'fingerPrint' => $data['keypair']['fingerprint']
        ];
    }

    function volumeAttachment($instanceID, $volumeID, $device)
    {
        $this->client->post(
            ['servers' => $instanceID, 'os-volume_attachments'],
            [
                'volumeAttachment' => [
                    'volumeId' => $volumeID,
                    'device'   => $device
                ]
            ]
        );
    }

    function getVolumeAttachments($instanceId)
    {
        return $this->client->get(
            ['servers' => $instanceId, 'os-volume_attachments']
        )['volumeAttachments'];
    }

    function volumeDeattachment($instanceID, $attachment_id)
    {
        $this->client->delete(
            [
                'servers'               => $instanceID,
                'os-volume_attachments' => $attachment_id
            ]
        );
    }

    function volumesList($instanceID)
    {
        $data = $this->client->get([
            'servers' => $instanceID,
            'os-volume_attachments'
        ]);
    }

    function addFlavorAccess($flavor, $tenantID)
    {
        $this->client->post([
            'flavors' => $flavor,
            'action'
        ], [
            'addTenantAccess' => [
                'tenant' => $tenantID
            ]
        ]);
    }

    function assignSecurityGroupVPS($id, $groupName)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'addSecurityGroup' => [
                'name' => $groupName
            ]
        ]);

        return true;
    }

    function unassignSecurityGroupVPS($id, $groupName)
    {
        $this->client->post([
            'servers' => $id,
            'action'
        ], [
            'removeSecurityGroup' => [
                'name' => $groupName
            ]
        ]);

        return true;
    }

    function getSecurityGroupList($id)
    {
        return $this->client->get([
            'servers' => $id,
            'os-security-groups'
        ]);

    }

    function getMetadata($id)
    {
        return $this->client->get([
            'servers' => $id,
            'metadata'
        ]);

    }

    function updateMetadata($id, $metadata = [])
    {
        return $this->client->put(sprintf("servers/%s/metadata", $id),
            [
                'metadata' => (object)$metadata
            ]);
    }

    function swapVolume($vmID, $oldVolume, $newVolume)
    {

        return $this->client->put([
            'servers'               => $vmID,
            'os-volume_attachments' => $oldVolume
        ], [
            "volumeAttachment" => [
                "volumeId" => $newVolume,
            ]
        ]);

    }

    function getAvailableAvailabilityZone()
    {
        $zones = $this->client->get('os-availability-zone')['availabilityZoneInfo'];

        $data = [];
        foreach($zones as $zone)
        {
            if($zone['zoneState']['available'] == true)
            {
                $data[] = $zone;
            }
        }

        return $data;
    }

}
