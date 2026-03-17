<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions;

use Exception;

class OSException extends Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param array $debugData
     */
    function __construct($message = "", $code = 0, $debugData = [])
    {
        parent::__construct($message, $code);

        foreach ($debugData as $name => $values)
        {
            if (property_exists($this, $name))
            {
                $this->{$name} = $values;
            }
        }
    }
}
