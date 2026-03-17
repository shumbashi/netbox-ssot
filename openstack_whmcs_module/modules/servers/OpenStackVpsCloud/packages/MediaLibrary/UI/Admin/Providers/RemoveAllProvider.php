<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\LibraryPathHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services\GalleryFileManager;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Events\RemovedAllFilesFromGallery;
use function ModulesGarden\OpenStackVpsCloud\Core\fire;

class RemoveAllProvider extends CrudProvider
{
    public function delete()
    {
        $fileManager = new GalleryFileManager(LibraryPathHelper::getPath());
        $fileManager->removeAll();

        fire(RemovedAllFilesFromGallery::class);
    }
}