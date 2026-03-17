<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\Source\CustomMessageException;

class SourceLanguageNotFound extends CustomMessageException
{
    public static string $defaultMessage = "sourceLanguageNotFound";
}