<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Services;

use DateTime;
use DateTimeZone;
use Exception;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\AbstractService;

class Gnocchi extends AbstractService
{
    const USEENDPOINT = 'metric';
    const VERSION     = 'v1';

    private $OpenStackTimeZone = 'UTC';
    private $myTimeZone = 'Europe/Warsaw';

    function testEndpoint()
    {
        $result = $this->client->get('', []);
        return $result ? true : false;
    }

    function setTimeZones($openStack, $local)
    {
        $this->OpenStackTimeZone = $openStack;
        $this->myTimeZone        = $local;
    }

    public function getResources(string $original_resource_id = '')
    {
        $json = $original_resource_id ? ['like' => ["original_resource_id" => "%$original_resource_id%"]] : [];
        $resources = $this->client->post("search/resource/instance", $json);
        $all = $this->client->post("search/resource/generic", $json);

        foreach ($resources as $key => $instance)
        {
            foreach ($all as $object)
            {
                if (strpos($object["original_resource_id"], $instance["id"]) !== false && is_array($object["metrics"]))
                {
                    $resources[$key]["metrics"] = array_merge((array)$resources[$key]["metrics"], $object["metrics"]);
                }
            }
        }

        return $resources;
    }

    public function getSamples($metrics, $start)
    {
        $configs = ["start" => $this->parseDate($start)];

        if (!empty($metrics))
        {
            foreach ($metrics as $resource => $metric)
            {
                //API throws exception on empty response
                try
                {
                    $configs = array_merge($configs, $metric["config"]);

                    //$configs["metric"] = $metric;
                    // $meters[]          = $this->client->get("aggregation/metric", $configs);
                    $res = $this->client->get("metric/{$metric["id"]}/measures", $configs);
                    unset($res['headers']);
                    $meters[$resource] = $res;
                }
                catch (Exception $ex)
                {
                    $meters[$resource] = [];
                }
            }
        }

        return $meters;
    }

    private function parseDate($date)
    {
        $myDateTime = new DateTime($date, new DateTimeZone($this->myTimeZone));
        $myDateTime->setTimeZone(new DateTimeZone($this->OpenStackTimeZone));
        return $myDateTime->format('Y-m-d\TH:i:s');
    }

}
