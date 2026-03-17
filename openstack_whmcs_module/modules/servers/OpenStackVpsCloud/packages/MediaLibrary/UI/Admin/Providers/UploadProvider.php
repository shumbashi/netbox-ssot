<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\FileExtensionsHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\LibraryPathHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services\GalleryFileManager;

class UploadProvider extends CrudProvider
{
    public function upload()
    {
        $request = Request::createFromGlobals();

        $formData     = $request->files->all()['formData'];
        $uploadedFile = $formData['uploadFileField'];

        if (empty($uploadedFile))
        {
            throw new \Exception("emptyUploadedFile");
        }

        if (!is_a($uploadedFile, \Symfony\Component\HttpFoundation\File\UploadedFile::class))
        {
            throw new \Exception("incorrectFileFound");
        }

        $fileManager = new GalleryFileManager(LibraryPathHelper::getPath(), FileExtensionsHelper::getAvailable());
        $fileManager->upload($uploadedFile);
    }
}