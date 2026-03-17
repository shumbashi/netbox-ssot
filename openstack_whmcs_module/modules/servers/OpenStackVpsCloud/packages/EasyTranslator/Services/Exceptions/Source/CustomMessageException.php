<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\Source;


abstract class CustomMessageException extends \Exception
{
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message ?: static::$defaultMessage, $code, $previous);
    }

}