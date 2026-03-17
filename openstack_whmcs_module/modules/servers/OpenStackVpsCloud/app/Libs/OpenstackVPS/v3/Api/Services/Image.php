<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;

class Image extends AbstractService
{
    const USEENDPOINT = 'image';
    const VERSION     = 'v2';

    function testEndpoint()
    {
        $this->client->get('images');
    }

    function listImages()
    {
        $images   = [];
        $nextPage = 'images';
        while ($nextPage)
        {
            $response = $this->client->get($nextPage);

            if (isset($response['next']))
            {
                $nextPage = basename($response['next']);
            }
            else
            {
                $nextPage = false;
            }

            foreach ($response['images'] as $row) {
                $images[$row['id']] = array_filter([
                    'id' => $row['id'],
                    'UUID' => $row['id'],
                    'name' => $row['name'],
                    'minDisk' => $row['min_disk'],
                    'minRam' => $row['min_ram'],
                    'size' => (!empty($response['size'])) ? $response['size'] : null,
                    'visibility' => $row['visibility'],
                    'diskFormat' => $row['disk_format'],
                    'status' => $row['status']
                ]);
            }

        }
        return $images;
    }


    //TODO: Refactor into filtering
    function listBackups()
    {
        $backups = [];

        $nextPage = 'images';
        while ($nextPage)
        {
            $response = $this->client->get($nextPage);

            if (isset($response['next']))
            {
                $nextPage = basename($response['next']);
            }
            else
            {
                $nextPage = false;
            }

            foreach ($response['images'] as $row)
            {
                if ($row['image_type'] == 'backup')
                {
                    $backups[$row['id']] = [
                        'id'      => $row['id'],
                        'name'    => $row['name'],
                        'minDisk' => $row['min_disk'],
                        'minRam'  => $row['min_ram'],
                        'created' => $row['created_at'],
                        'source'  => $row['instance_uuid'],
                        'status'  => $row['status']
                    ];
                }
            }

        }
        return $backups;
    }

    function getImage($id)
    {
        $response = $this->client->get(['images' => $id]);

        return [
            'name' => $response['name'],
            'UUID' => $response['id'],
            'visibility' => $response['visibility'],
            'minDisk' => $response['min_disk'],
            'size' => (!empty($response['size'])) ? $response['size'] : null,
            'minRam' => $response['min_ram'],
        ];
    }

    function createImageFromISOURL($name, $url)
    {
        $config = [
            'is_public'        => false,
            'protected'        => false,
            'disk_format'      => 'iso',
            'container_format' => 'bare',
            'min_disk'         => 0,
            'min_ram'          => 0,
            'name'             => $name,

        ];
        /*
        dump($config);
        $id = $this->client->post(
            'images',
$config);
        
        dump($id);
        $b = $this->client->put(array('images'=>'03a2e32c-2a33-4533-b313-dcf25a0a898e'),array('copy_from' => $url));
        dump($b);*/
    }

    function deleteImage($id)
    {
        $this->client->delete(['images' => $id]);
    }

}