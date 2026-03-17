<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\Source\CustomMessageException;

class InvalidTargetLanguage extends CustomMessageException
{
    public static string $defaultMessage = "invalidTargetLanguage";
}