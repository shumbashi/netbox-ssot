<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services;

use DirectoryIterator;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Enums\Settings;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\FileExtensionsHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\FileUniqueNameGenerator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GalleryFileManager
{
    private string $targetDirectory;
    private array $availableExtensions;

    public function __construct(string $targetDirectory, array $availableExtensions = [])
    {
        $this->targetDirectory = rtrim($targetDirectory, '/') . DIRECTORY_SEPARATOR;
        $this->availableExtensions = array_map(function ($value) { return strtolower($value); }, $availableExtensions);
    }

    public function getUploadedFilesList()
    {
        if (file_exists($this->targetDirectory) && !is_readable($this->targetDirectory))
        {
            throw new \Exception('targetPathIsNotReadable');
        }

        $files = array_map('basename', glob($this->generateSearchPattern(), GLOB_BRACE));

        sort($files, SORT_FLAG_CASE|SORT_STRING|SORT_NATURAL);

        return array_values(array_filter($files, function($file) {
            return !in_array($file, ['.', '..']);
        }));
    }

    public function upload(UploadedFile $file)
    {
        $fileName = $this->generateFileName($file);

        if (file_exists($this->targetDirectory . $fileName))
        {
            throw new \Exception('fileAlreadyExists');
        }

        if (!empty($this->availableExtensions) && !FileExtensionsHelper::checkExtension($file->getClientOriginalExtension(), $this->availableExtensions))
        {
            throw new \Exception('incorrectFileExtensionUploaded');
        }

        if (file_exists($this->targetDirectory) && !is_writable($this->targetDirectory))
        {
            throw new \Exception('targetPathIsNotWritable');
        }

        try
        {
            $file->move($this->targetDirectory, $fileName);
        }
        catch (FileException $e)
        {
            throw new \Exception('uploadFileError');
        }

        return $fileName;
    }

    public function remove($fileName)
    {
        $this->checkFullGalleryPathPermissions();

        $removeResult = unlink($this->targetDirectory . $fileName);
        if (!$removeResult)
        {
            throw new \Exception('removeFileError');
        }
    }

    public function removeAll()
    {
        if (!file_exists($this->targetDirectory))
        {
            throw new \Exception('noFilesFound');
        }

        $this->checkFullGalleryPathPermissions();

        foreach (new DirectoryIterator($this->targetDirectory) as $fileInfo)
        {
            if (!$fileInfo->isDot())
            {
                unlink($fileInfo->getPathname());
            }
        }
    }

    public function generateFileName(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();

        return Config::get(Settings::UNIQUE_NAMES, false) ?
            FileUniqueNameGenerator::generateUniqueFileName($originalFilename, $extension) :
            $originalFilename . "." . $extension;
    }

    protected function generateSearchPattern(): string
    {
        $availableExtensions = empty($this->availableExtensions) ? '*' :
            FileExtensionsHelper::buildSearchPatterFromExtensions($this->availableExtensions);

        return "{$this->targetDirectory}*.{$availableExtensions}";
    }

    protected function checkFullGalleryPathPermissions()
    {
        if (!file_exists($this->targetDirectory))
        {
            return;
        }

        if (!is_readable($this->targetDirectory))
        {
            throw new \Exception('targetPathIsNotReadable');
        }

        if (!is_writable($this->targetDirectory))
        {
            throw new \Exception('targetPathIsNotWritable');
        }
    }
}