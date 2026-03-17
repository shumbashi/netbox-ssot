<?php

/* * ********************************************************************
 * OpenStack_VPS product developed. (2017-05-12)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\assoc;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;

/**
 * Description of Identity
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @version 1.0.0
 */
class Identity extends AbstractService
{

    const USEENDPOINT = 'identity';
    const VERSION     = 'v3';

    private static $useRegion = null;
    private $regions = [];

    function getVersion()
    {
        return $this->client->get();
    }

    /**
     * Login into KeyStone and return token for request and endpoints
     *
     * @param string $tenantName
     * @param string $tenantID ;
     * @param string $APIUsername
     * @param string $APIPassword
     * @return assoc token & endpoints
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    public function tokens($tenantName, $tenantID, $username, $password, $domainName = null, $certificate = '', $projectName = '')
    {
        $domainName        = (!empty($domainName)) ? $domainName : "Default";
        $explodeDomainName = explode('|', $domainName);

        if ($explodeDomainName[0] == "id")
        {
            $domain = [
                'id' => $explodeDomainName[1]
            ];
        }
        else
        {
            $domain = [
                'name' => $domainName
            ];
        }

        $config = [
            'auth' => [
                'identity' => [
                    'methods'  => ['password'],
                    'password' => [
                        'user' => [
                            'name'     => $username,
                            'domain'   => $domain,
                            'password' => $password,
                        ],
                    ],
                ],
                "scope"    => [
                    "project" => [
                        'id'     => $tenantID,
                        'name'   => $projectName,
                        'domain' => $domain
                    ],
                ],
            ]];


        $response = $this->client->post('auth/tokens', $config, $certificate);

        $output = [
            'token'   => $response['headers']['x-subject-token'],
            'catalog' => $response['token']['catalog']
        ];


        foreach ($response['token']['catalog'] as $catalog)
        {
            foreach ($catalog['endpoints'] as $endpoint)
            {
                $this->regions[$endpoint['region_id']] = $endpoint['region'];
            }
        }


        if (self::getUseRegion() && in_array(self::getUseRegion(), $this->regions))
        {
            foreach ($output['catalog'] as $k => $catalog)
            {

                foreach ($catalog['endpoints'] as $k2 => $endpoint)
                {
                    if (self::getUseRegion() != $endpoint['region'])
                    {
                        unset($output['catalog'][$k]['endpoints'][$k2]);
                        continue;
                    }
                }
            }
        }


        return $output;
    }

    static function getUseRegion()
    {
        return self::$useRegion;
    }

    static function setUseRegion($useRegion)
    {
        self::$useRegion = $useRegion;
    }

    /**
     * Return list of extensions
     *
     * @return assoc array key=TAG value=NAME
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     * @deprecated since version v3
     */
    public function getExtensions()
    {
        throw new Exception("Method " . __METHOD__ . " deprecated");
        $response = $this->client->get('extensions');

        $extensions = [];

        foreach ($response['extensions']['values'] as $extension)
        {
            $extensions[]                           = [
                'name'  => $extension['name'],
                'alias' => $extension['alias']
            ];
            $this->_extensions[$extension['alias']] = $extension['name'];
        }

        return $extensions;
    }

    public function getGroups()
    {
        return $this->client->get('groups');
    }

    function testEndpoint()
    {
        return $this->client->get();
    }

    public function getRegions()
    {

        return $this->regions;
    }

    /**
     * List Tenants
     *
     * @return assoc array value=[id,name,enable]
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    public function listsTenants()
    {
        $response = $this->client->get('projects');

        $return = [];
        foreach ($response['projects'] as $tenant)
        {
            $return[] = [
                'id'     => $tenant['id'],
                'name'   => $tenant['name'],
                'enable' => $tenant['enabled']
            ];
        }

        return $return;
    }

    /**
     * Get Tenant ID By Name
     *
     * @param string $name
     * @return string ID
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    public function getTenantIDByName($name)
    {
        $response = $this->client->get('tenants', ['name' => $name]);

        return $response['tenant']['id'];
    }

    /**
     * Create Tenant
     *
     * @param string $name
     * @param string $description
     * @param string $enable
     * @return string new tenant ID
     * @throws OSException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    public function createTenant($name, $description = null, $enable = true)
    {
        if ($description == null)
        {
            $description = $name;
        }

        $output = $this->client->post('tenants', [
            'tenant' => [
                'name'        => $name,
                'description' => $description,
                'enabled'     => $enable
            ]
        ]);

        return $output['tenant']['id'];
    }

    /**
     * Update Tenant
     *
     * @param string $id
     * @param string $name
     * @param string $description
     * @param bool $enable
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    public function updateTenant($id, $name, $description = null, $enable = true)
    {
        if ($description == null)
        {
            $description = $name;
        }

        $this->client->post(
            [
                'tenants' => $id
            ],
            [
                'tenant' => [
                    'name'        => $name,
                    'description' => $description,
                    'enabled'     => $enable
                ]
            ]);
    }

    /**
     * Delete Tenant by ID
     *
     * @param string $id
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    public function deleteTenant($id)
    {
        $this->client->delete(['tenants' => $id]);
    }

    /**
     * Get Tenant Users
     *
     * @param string $id
     * @return assoc array value=[id,name]
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    public function getTenantUsers($id)
    {
        $response = $this->client->get([
            'tenants' => $id,
            'users'
        ]);

        $output = [];
        foreach ($response['users'] as $user)
        {
            $output[] = [
                'id'   => $user['id'],
                'name' => $user['name']
            ];
        }
        return $output;
    }

    public function getProject(string $id)
    {
        $response = $this->client->get('projects/' . $id);
        return $response['project'];
    }

    /**
     * Create Keystone Users
     *
     * @param string $name
     * @param string $password
     * @param string $mail
     * @param bool $enabled
     * @return string new user ID
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    public function createUser($name, $password, $mail, $tenantID, $enabled = true)
    {
        $data = [
            'user' => [
                'name'     => $name,
                'email'    => $mail,
                'enabled'  => $enabled,
                'password' => $password,
                'tenantId' => $tenantID
            ]
        ];

        $output = $this->client->post('users', $data);

        return $output['user']['id'];
    }

    /**
     * Update Keystone User
     *
     * @param string $id
     * @param string $name
     * @param string $mail
     * @param bool $enabled
     * @throws OpenStackApiException
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    public function updateUser($id, $name, $mail, $enabled = true)
    {
        $this->client->put(
            [
                'users' => $id
            ],
            [
                'user' => [
                    'name'    => $name,
                    'email'   => $mail,
                    'enabled' => $enabled
                ]
            ]);
    }

    public function changeUserPassword($id, $password)
    {
        $this->client->put(
            [
                'users' => $id
            ],
            [
                'user' => [
                    'password' => $password
                ]
            ]);
    }

    /**
     * Delete Keystone user
     *
     * @param string $id
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    public function deleteUser($id)
    {
        $this->client->delete(['users' => $id]);
    }

    /**
     * Return list roles
     *
     * @return assoc array value=[id,name,enable]
     * @throws OpenStackApiException
     * @api
     * @author Michal Czech <michael@modulesgarden.com>
     */
    public function listRoles()
    {
        $response = $this->client->get(['OS-KSADM' => 'roles']);

        $output = [];
        foreach ($response['roles'] as $role)
        {
            $output[] = [
                'id'      => $role['id'],
                'name'    => $role['name'],
                'enabled' => ($role['name'] == 'admin') ? true : $role['enabled']
            ];
        }
        return $output;
    }

    /**
     * Add user to tenant
     *
     * @param string $userID
     * @param string $tenantID
     * @param string $roleID
     * @author Michal Czech <michael@modulesgarden.com>
     * @api
     */
    public function addUserToTenant($userID, $tenantID, $roleID)
    {
        $this->client->put([
            'tenants' => $tenantID,
            'users'   => $userID,
            'roles'   => 'OS-KSADM',
            $roleID
        ]);
    }

    public function getUserByName($name)
    {
        $response = $this->client->get(['users'], ['name' => $name]);
        return [
            'id' => $response['user']['id']
        ];
    }

}
