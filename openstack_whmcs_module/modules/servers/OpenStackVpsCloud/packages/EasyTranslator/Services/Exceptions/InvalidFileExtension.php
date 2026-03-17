<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\Source\CustomMessageException;

class InvalidFileExtension extends CustomMessageException
{
    public static string $defaultMessage = "invalidFileExtension";
}