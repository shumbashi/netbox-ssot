<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\Source\CustomMessageException;

class InvalidNewLanguageName extends CustomMessageException
{
    public static string $defaultMessage = "invalidNewLanguageName";
}