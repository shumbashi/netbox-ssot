<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Http;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Helpers\JsonContentParser;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Http\Validator\ResponseValidator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use \ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;

class HttpClient
{
    public ?string $token;
    protected ?string $endPoint;
    protected ?string $certificate;
    protected ?bool $debug;
    private array $debugData = [];


    /**
     * @param string $endPoint
     * @param string $token
     * @param array $params
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function __construct($endPoint, $token, array $params = [], $certificate = "", ?bool $debug = false)
    {
        $this->endPoint = $endPoint;
        $this->token = $token;
        $this->debug = $debug;

        foreach ($params as $property => $value)
        {
            if (property_exists($this, $property))
            {
                if (empty($this->{$property}))
                {
                    $this->{$property} = $value;
                }
            }
        }
        if (!empty($certificate))
        {
            $this->certificate = $certificate;
        }
    }

    function testEndpoint()
    {
        $this->get('extensions');
    }

    private function _request($method, $params = null, $content = [], $certificate = '', $headers = [])
    {
        $this->debugData = [
            'url'              => '',
            'request'          => [],
            'request_headers'  => [],
            'response'         => [],
            'response_headers' => []
        ];

        $url = $this->endPoint;

        $builder = new ParamStringBuilder($params);
        $paramString = $builder->buildParamString();

        $url .= $paramString;

        $data2send = null;

        if ($method != 'GET')
        {

            if ($content)
            {
                if (is_array($content))
                {
                    $data2send = json_encode($content);
                }
                else
                {
                    $data2send = $content;
                }
            }
        }
        else
        {
            if (!empty($content)) {
                $url .= '?' . http_build_query($content);
            }
        }

        try {
            $this->debugData['request'] = $data2send ?: [];
            return $this->_send($method, $url, $data2send, $headers);
        }
        catch (\Throwable $throwable)
        {
//           Logger::critical(LoggerMessages::EXCEPTION, [
//                'stacktrace' => $throwable->getTraceAsString()
//            ] + $this->debugData);

           throw $throwable;
        }
    }

    private function _send($method, $url, $data2send, $headers = [])
    {
        $parsedURL = parse_url($url);

        if (!empty($parsedURL['query']))
        {
            $parsedURL['path'] = $parsedURL['path'] . '?' . $parsedURL['query'];
        }

        $headers['Host'] = $parsedURL['host'];

        $headers['Content-Type'] = 'application/json';
        $headers['Accept']       = 'application/json';

        if (property_exists($this, 'token'))
        {
            if (!empty($this->token))
            {
                $headers['X-Auth-Token'] = $this->token;
            }
        }

        $headers['Content-length'] = strlen($data2send);
        $headers['Connection']     = 'close';

        $this->debugData['request_headers'] = $headers;

        $rawData = $method . ' ' . $parsedURL['path'] . " HTTP/1.1\r\n";

        foreach ($headers as $headerName => $headerValue)
        {
            $rawData .= "$headerName: $headerValue\r\n";
        }

        $rawData .= "\r\n";
        $rawData .= $data2send . "\r\n\r\n";

        if ($parsedURL['scheme'] == 'https')
        {
            $useURL = 'ssl://' . $parsedURL['host'];
        }
        else
        {
            $useURL = $parsedURL['host'];
        }

        if (empty($parsedURL['port']))
        {
            if ($parsedURL['scheme'] == 'https')
            {
                $port = '443';
            }
            else
            {
                $port = '80';
            }
        }
        else
        {
            $port = $parsedURL['port'];
        }


        $context = stream_context_create();

        stream_context_set_option($context, 'ssl', 'verify_host', false);
        stream_context_set_option($context, 'ssl', 'verify_peer_name', false);
        if (!empty($certificate))
        {
            stream_context_set_option($context, 'ssl', 'verify_peer', true);
            stream_context_set_option($context, 'ssl', 'cafile', $certificate);
            stream_context_set_option($context, 'ssl', 'crypto_method', STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }
        else
        {
            stream_context_set_option($context, 'ssl', 'verify_peer', false);
        }


        $useURL .= ":" . $port;

        $this->debugData['url'] = $url;

        $connectCode    = '';
        $connectMessage = '';

        $fp = stream_socket_client($useURL, $connectCode, $connectMessage, Api::getInstance()->getTimeOut(), STREAM_CLIENT_CONNECT, $context);
        if ($connectCode)
        {
            throw new OpenStackApiException('Connection to: ' . $parsedURL['host'] . ':' . $parsedURL['port'] . ' failed. ' . $connectMessage, $connectCode, $this->debugData);
        }

        if (!$fp)
        {
            throw new OpenStackApiException('Connection to: ' . $parsedURL['host'] . ':' . $parsedURL['port'] . ' failed', 1, $this->debugData);
        }
        fputs($fp, $rawData);

        $start = microtime(true);

        $diff = 0;

        $maxSeconds = Api::getInstance()->getTimeOut();

        $response = null;

        //MAX MB
        $maxMB = 50;

        $max = $maxMB * 1024 * 1024;

        while ($diff < $maxSeconds && !feof($fp))
        {
            $response .= fgets($fp, 128);

            $used = strlen($response);
            if ($used > $max)
            {
                $used = $used / 1024 / 1024;
                throw new OpenStackApiException('Response use ' . $used . ' from max ' . $maxMB . ' reach memory limit', 30, $this->debugData);
            }
            $diff = microtime(true) - $start;
        }


        if ($diff >= $maxSeconds)
        {
            throw new OpenStackApiException('Response timed out after ' . $diff, 28, $this->debugData);
        }

        if (empty($response))
        {
            throw new OpenStackApiException('Empty Response', 1, $this->debugData);
        }

        $this->debugData['response'] = $response;

        $toHeader = true;
        $body     = null;

        foreach (explode("\r\n", $response) as $i => $line)
        {
            if (!empty($line))
            {
                if ($toHeader)
                {
                    if ($i === 0)
                    {
                        if (preg_match("/(?<http>[A-Z]{4,5})\/(?<http_version>[0-9\.]+) (?<http_code>[0-9]{3})/", trim($line), $headers))
                        {
                            $headers = [
                                'http'         => $headers['http'],
                                'http_version' => $headers['http_version'],
                                'http_code'    => $headers['http_code']
                            ];
                        }
                        else
                        {

                            throw new OpenStackApiException(strip_tags($line));
                        }
                    }
                    else
                    {
                        [$key, $value] = explode(': ', $line);

                        if ($key == 'Connection' && $value == 'close')
                        {
                            $toHeader = false;
                        }
                        else
                        {
                            $headers[$key] = $value;
                        }
                    }
                }
                else
                {
                    $body .= $line;
                }
            }
            else
            {
                $toHeader = false;
            }
        }

        $headerParser = new HttpHeadersParser();
        $decodeResponse = $headerParser->httpParseHeaders($response);

        $body = $decodeResponse['body'];

        $this->debugData['response_headers'] = $headers;
        $this->debugData['response_header']['request_time'] = $diff;

        $jsonParser = new JsonContentParser();
        $parsedBody = $jsonParser->parse($body);

        $this->debugData['response'] = $parsedBody;

        if ($this->debug) {
            $this->log($this->debugData);
        }

        try {
            ResponseValidator::validate($parsedBody, $headers['http_code'], $this->debugData, $method);
        }
        catch (\Throwable $t)
        {
            $this->debugData['response'] = $parsedBody;
            if ($this->debug) {
                $this->log($this->debugData);;
            }

            throw $t;
        }

        if (
            ($method == "DELETE") || ($method == "POST" && in_array($headers['http_code'], [202, 204]) && empty($body))
        )
        {
            return true;
        }

        if ($headers['http_code'] == "202" && empty($body))
        {
            return [];
        }

        if (empty($body))
        {
            throw new OpenStackApiException('Empty Response', 1, $this->debugData);
        }

        if ($body == '[]' || $body == '{}')
        {
            return [];
        }

        $headers = array_flip(array_map('strtolower', array_flip($headers)));
        if (isset($headers['x-subject-token']))
        {
            $parsedBody['headers'] = $headers;
        }
        else
        {
            $headers['vary']                   = $decodeResponse['vary'];
            unset($decodeResponse['vary']);

            $decodeResponse = array_flip(array_map('strtolower', array_flip($decodeResponse)));

            $headers['x-subject-token']        = $decodeResponse['x-subject-token'];
            $headers['x-openstack-request-Id'] = $decodeResponse['x-openstack-request-Id'];

            $parsedBody['headers']             = $headers;
        }
        return $parsedBody;
    }

    /**
     * GET API Request
     * @param array $URI eg. array(value) or aray(key=>value)
     * @param array $query
     * @return array response
     * @throws OpenStackApiException
     */
    public function get($URI = [], $query = [], $certificate = '', $headers = [])
    {
        return $this->_request("GET", $URI, $query, $certificate, $headers);
    }

    /**
     * POST API Request
     * @param array $URI eg. array(value) or aray(key=>value)
     * @param array $JSON
     * @return array response
     * @throws OpenStackApiException
     */
    public function post($URI, $JSON = [], $certificate = '', $headers = [])
    {

        return $this->_request("POST", $URI, $JSON, $certificate, $headers);
    }

    /**
     * DELETE API Request
     * @param type $URI
     * @return bool
     * @throws OpenStackApiException
     */
    public function delete($URI)
    {
        return $this->_request("DELETE", $URI);
    }

    /**
     * PUT API Request
     * @param type $URI
     * @return mixed
     * @throws OpenStackApiException
     */
    public function put($URI, $JSON = [], $certificate = '')
    {
        return $this->_request("PUT", $URI, $JSON, $certificate = '');
    }

    /**
     * PUT API Request
     * @param type $URI
     * @return mixed
     * @throws OpenStackApiException
     */
    protected function patch($URI, $JSON = [], $certificate = '')
    {
        return $this->_request("PATCH", $URI, $JSON, $certificate);
    }

    protected function log(array $debugData)
    {
        \logModuleCall(
            ModuleConstants::getModuleName(),
            $debugData['url'],
            json_encode([
                'headers' => $debugData['request_headers'],
                'request' => $debugData['request']],
                JSON_PRETTY_PRINT
            ),
            json_encode([
                'headers' => $debugData['response_headers'],
                'response' => $debugData['response']],
                JSON_PRETTY_PRINT), 0, 0
        );
    }
}