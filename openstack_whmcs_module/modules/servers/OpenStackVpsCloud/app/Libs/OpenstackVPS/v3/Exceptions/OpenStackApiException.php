<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions;

/**
 * Description of OpenStackApiException
 *
 * @author inbs
 */
class OpenStackApiException extends OSException
{

    protected $host;
    protected $port;
    protected $path;
    protected $method;
    protected $scheme;
    protected $content;
    protected $response;
    protected $headers;

    /**
     * @param string $message
     * @param string $code
     * @param string $debugData
     * @author Michal Czech <michael@modulesgarden.com>
     */
    function __construct($message, $code = null, $debugData = [])
    {
        parent::__construct($message, $code, $debugData);
    }

    function toArray()
    {
        return [
            'host'     => $this->host,
            'port'     => $this->port,
            'path'     => $this->path,
            'method'   => $this->method,
            'content'  => $this->content,
            'response' => $this->response,
            'headers'  => $this->headers,
            'scheme'   => $this->scheme
        ];
    }

}
