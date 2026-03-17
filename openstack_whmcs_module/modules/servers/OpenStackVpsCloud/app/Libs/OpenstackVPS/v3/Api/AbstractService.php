<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Http\HttpClient;

abstract class AbstractService
{
    protected HttpClient $client;

    public function __construct(?string $endpoint, ?string $token, array $params = [], string $certificate = '', ?bool $debug = false)
    {
        $this->client = new HttpClient($endpoint, $token, $params, $certificate, $debug);
    }

    function testEndpoint()
    {
        $this->client->get('extensions');
    }
}
