<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;

class Volume extends AbstractService
{

    const USEENDPOINT = 'volumev3';
    const VERSION     = '';


    /**
     * Get Quota for Volumes
     * @return array resource=> value
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function getQuota()
    {
        $result = $this->client->get('limits');

        $output = [];
        foreach ($result['limits']['absolute'] as $name => $value)
        {
            $output[$name] = $value;
        }

        return $output;
    }

    /**
     * Set Quota Values
     * @param string $tenantID
     * @param array $data
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function setQuota($tenantID, $data)
    {
        $toSet = [
            'gigabytes' => $data['maxTotalVolumeGigabytes'],
            'volumes'   => $data['maxTotalVolumes']
        ];

        if (!empty($data['maxTotalSnapshots']) || $data['maxTotalSnapshots'] === '0')
        {
            $toSet['snapshots'] = $data['maxTotalSnapshots'];
        }

        $this->client->put(
            [
                'os-quota-sets' => $tenantID
            ],
            [
                'quota_set' => $toSet
            ]
        );
    }

    function listSnapshots()
    {
        $result = $this->client->get('snapshots');

        $list = [];
        foreach ($result['snapshots'] as $snapshot)
        {
            $list[] = [
                'UUID'     => $snapshot['id'],
                'id'       => $snapshot['id'],
                'status'   => $snapshot['status'],
                'volumeID' => $snapshot['volume_id'],
                'size'     => $snapshot['size'],
                'name'     => $snapshot['name'],
                'created'  => $snapshot['created_at'],
                'metadata' => $snapshot['metadata']
            ];
        }

        return $list;
    }

    function listVolumes()
    {
        $result = $this->client->get(['volumes', 'detail']);

        $output = [];
        foreach ($result['volumes'] as $volume)
        {

            $output[] = [
                'id'          => $volume['id'],
                'UUID'        => $volume['id'],
                'name'        => $volume['name'],
                'description' => $volume['description'],
                'status'      => $volume['status'],
                'attach'      => isset($volume['attachments'][0]) ? $volume['attachments'][0]['server_id'] : null,
                'bootable'    => $volume['bootable'],
                'size'        => $volume['size'],
                'created'     => $volume['created_at'],
                'type'        => $volume['volume_type']
            ];
        }

        return $output;
    }

    function createVolume($size, $name, $imageRef = null, $volumeID = null, $snapshotID = null, $bootable = true, $type = null)
    {
        $config = [
            'size' => $size
        ];

        if ($name)
        {
            $config['name'] = $name;
        }

        if ($imageRef)
        {
            $config['imageRef'] = $imageRef;
        }

        if ($volumeID)
        {
            $config['source_volid'] = $volumeID;
        }

        if ($snapshotID)
        {
            $config['snapshot_id'] = $snapshotID;
        }

        if ($type)
        {
            $config['volume_type'] = $type;
        }

        $config['bootable'] = $bootable;


        $data = $this->client->post('volumes', ['volume' => $config]);

        return [
            'id' => $data['volume']['id']
        ];
    }

    function createVolumeFromSnapshot($snapshotID, $name = null)
    {
        if(!$name)
        {
            $name = $snapshotID;
        }
        $data = $this->client->post('volumes', [
            'volume' => [
                'snapshot_id' => $snapshotID,
                'name' => $name,
            ]
        ]);

        return [
            'id' => $data['volume']['id']
        ];
    }

    function deleteVolume($UUID)
    {
        $this->client->delete(['volumes' => $UUID]);
    }

    function getVolume($UUID)
    {
        $data   = $this->client->get(['volumes' => $UUID]);
        $volume = $data['volume'];

        return [
            'id'           => $volume['id'],
            'UUID'         => $volume['id'],
            'name'         => $volume['name'],
            'description'  => $volume['description'],
            'status'       => $volume['status'],
            'attachID'     => isset($volume['attachments'][0]) ? $volume['attachments'][0]['id'] : null,
            'attachServer' => isset($volume['attachments'][0]) ? $volume['attachments'][0]['server_id'] : null,
            'attachDevice' => isset($volume['attachments'][0]) ? $volume['attachments'][0]['device'] : null,
            'bootable'     => $volume['bootable'],
            'size'         => $volume['size'],
            'created'      => $volume['created_at'],
            'type'         => $volume['volume_type']
        ];
    }

    function extendVolume($UUID, $newSize)
    {
        return $this->client->post(
            ['volumes' => $UUID, 'action'],
            [
                'os-extend' => [
                    'new_size' => $newSize
                ]
            ],
            '',
            [
                'OpenStack-API-Version' => 'volume 3.42'
            ]
        );
    }

    function createSnapshot($id, $name, $metadata = [])
    {
        $this->client->post('snapshots', [
            'snapshot' => [
                'name'      => $name,
                'volume_id' => $id,
                'force'     => true,
                'metadata'  => $metadata
            ]
        ]);
    }

    function createBackup($id, $name, $metadata = [], $force = true)
    {
        $this->client->post('backups', [
            'backup' => [
                'name'        => $name,
                'force'       => $force,
                'volume_id'   => $id,
                'description' => 'WHMCS_SERVICE_' . $metadata['whmcs-hosting-id']
            ]
        ]);
    }

    function restoreVolume($backupID, $volumeID)
    {
        $this->client->post([
            'backups' => $backupID,
            'restore'
        ], [
            'restore' => [
                'volume_id' => $volumeID,
            ]
        ]);
    }

    function deleteVolumeBackup($UUID)
    {
        $this->client->delete(['backups' => $UUID]);
    }

    function listVolumeBackups()
    {
        $result = $this->client->get('backups/detail');
        $list   = [];
        foreach ($result['backups'] as $snapshot)
        {
            $list[] = [
                'UUID'        => $snapshot['id'],
                'id'          => $snapshot['id'],
                'status'      => $snapshot['status'],
                'volumeID'    => $snapshot['volume_id'],
                'size'        => $snapshot['size'],
                'name'        => $snapshot['name'],
                'created'     => $snapshot['created_at'],
                'description' => $snapshot['description']
            ];
        }

        return $list;
    }

    function deleteSnapshot($UUID)
    {
        $this->client->delete(['snapshots' => $UUID]);
    }

    function listBackups()
    {
        $data = $this->client->get('backups');
    }

    function getTypes()
    {
        $data = $this->client->get('types');

        return $data['volume_types'];
    }


    function getSnapshots()
    {
        $data = $this->client->get([

        ]);

        return $data['volume_types'];
    }


}