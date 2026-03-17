<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services;

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Libs\ParseFile\ParsersFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\IncorrectFileUploaded;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\InvalidFileExtension;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\Exceptions\InvalidTargetLanguage;

class LangsImport
{
    const ALLOWED_EXTENSIONS = ['json'];

    public static function getAllowedExtensions():array
    {
        return self::ALLOWED_EXTENSIONS;
    }

    public function import($targetLanguage, $uploadedFile)
    {
        if (empty($targetLanguage) || in_array($targetLanguage, Langs::getUsedLangs()))
        {
            throw new InvalidTargetLanguage();
        }

        if (empty($uploadedFile) || !is_a($uploadedFile, \Symfony\Component\HttpFoundation\File\UploadedFile::class))
        {
            throw new IncorrectFileUploaded();
        }

        $extension = $uploadedFile->getClientOriginalExtension();

        if (!in_array($extension, self::ALLOWED_EXTENSIONS))
        {
            throw new InvalidFileExtension();
        }

        $content = file_get_contents($uploadedFile->getPathname());

        $fileParser = ParsersFactory::createFromFileExtension($extension);
        $translations = $fileParser->toArray($content);

        Langs::saveLanguages($targetLanguage, $translations);
    }
}