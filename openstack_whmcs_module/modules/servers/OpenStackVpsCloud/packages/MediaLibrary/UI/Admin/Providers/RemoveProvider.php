<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\LibraryPathHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services\GalleryFileManager;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Events\RemovedFileFromGallery;
use function ModulesGarden\OpenStackVpsCloud\Core\fire;

class RemoveProvider extends CrudProvider
{
    public function delete()
    {
        $fileName = $this->formData->get('fileName');

        if (empty($fileName))
        {
            throw new \Exception("incorrectFilename");
        }

        $fileManager = new GalleryFileManager(LibraryPathHelper::getPath());
        $fileManager->remove($fileName);

        fire(RemovedFileFromGallery::class, (object)['name' => $fileName]);
    }
}