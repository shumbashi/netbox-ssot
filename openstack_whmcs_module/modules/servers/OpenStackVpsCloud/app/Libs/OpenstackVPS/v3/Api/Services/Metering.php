<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use DateTime;
use DateTimeZone;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;

class Metering extends AbstractService
{
    const USEENDPOINT = 'metering';
    const VERSION     = 'v2';

    private $OpenStackTimeZone = 'UTC';
    private $myTimeZone = 'Europe/Warsaw';

    function testEndpoint()
    {
        $this->client->get('meters', []);
    }

    function setTimeZones($openStack, $local)
    {
        $this->OpenStackTimeZone = $openStack;
        $this->myTimeZone        = $local;
    }

    function getMeters($tenantID, $vmID)
    {

        $params = [
            'project_id' => $tenantID
        ];

        if ($vmID)
        {
            $params['resource_id'] = $vmID;
        }

        $meters = $this->client->get('meters', []
        //$this->queryParser(array(),'q',$params)
        );

        $data = [];
        foreach ($meters as $meter)
        {
            $data[$meter['name']] = [
                'name' => $meter['name'],
                'type' => $meter['type'],
                'unit' => $meter['unit']
            ];
        }
    }

    function getSamples($name, $tenantID, $start = '-24 hours', $stop = '+1 hours')
    {
        $configs = [];

        $configs = $this->queryParser($configs, 'q', [
            ['name' => 'project_id', 'type' => 'eq', 'value' => $tenantID],
            ['name' => 'timestamp', 'type' => 'ge', 'value' => $this->parseDate($start)],
            ['name' => 'timestamp', 'type' => 'lt', 'value' => $this->parseDate($stop)]
        ]);

        $meters = $this->client->get(['meters' => $name],
            $configs
        );

        return $meters;
    }

    private function queryParser($input, $type, $data)
    {
        foreach ($data as $field => $value)
        {
            $input[] = [$type . '.field', $value['name']];
            if ($value['type'])
            {
                $input[] = [$type . '.op', $value['type']];
            }
            $input[] = [$type . '.value', $value['value']];
        }

        return $input;
    }

    private function parseDate($date)
    {
        $myDateTime = new DateTime($date, new DateTimeZone($this->myTimeZone));
        $myDateTime->setTimeZone(new DateTimeZone($this->OpenStackTimeZone));
        return $myDateTime->format('Y-m-d\TH:i:s');
    }

    function getComputeMeter($name, $tenantID, $start = '-12 hours', $stop = '+1 hours')
    {
        //valid names 
        //instance
        //memory
        //vcpus
        //disk.ephemeral.size
        //disk.root.size
        //disk.read.bytes
        //disk.read.requests
        //disk.write.bytes
        //disk.write.requests
        //port
        //ip.floating
        //cpu
        //network.incoming.bytes
        //network.outgoing.bytes
        //image
        //image.size
        //subnet
        //router
        //cpu_util

        //valid keys: set(['end', 'start', 'metaquery', 'meter', 'project', 'source', 'user', 'start_timestamp_op', 'resource', 'end_timestamp_op']

        $configs = [];

        $configs = $this->queryParser($configs, 'q', [
            ['name' => 'project_id', 'type' => 'eq', 'value' => $tenantID],
            ['name' => 'timestamp', 'type' => 'ge', 'value' => $this->parseDate($start)],
            ['name' => 'timestamp', 'type' => 'lt', 'value' => $this->parseDate($stop)]
        ]);

        $configs = $this->queryParser($configs, 'q', []);

        $configs[] = ['groupby', 'resource_id'];

        $meters = $this->client->get(['meters' => $name, 'statistics'],
            $configs
        );

        $output = [];

        foreach ($meters as $meter)
        {
            $output[$meter['groupby']['resource_id']] = [
                'avg' => $meters[0]['avg'],
                'sum' => $meters[0]['sum']
                //   ,'unit'        => $meters[0]['unit']
                //   ,'min'         => $meters[0]['min']
                //    ,'max'         => $meters[0]['max']
            ];
        }

        return $output;
    }
}