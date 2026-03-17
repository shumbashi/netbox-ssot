<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Pages;

use ModulesGarden\OpenStackVpsCloud\Components\DropdownMenu\DropdownMenu;
use ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\MediaLibrary as MediaLibraryComponent;
use ModulesGarden\OpenStackVpsCloud\Components\MediaLibraryItem\MediaLibraryItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ObjectDataProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\ContentUrlGenerator;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\FileExtensionsHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\LibraryPathHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Services\GalleryFileManager;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Buttons\RemoveAllButton;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Buttons\UploadImageButton;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Modals\RemoveImageModal;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Modals\UploadImageModal;

class MediaLibrary extends MediaLibraryComponent implements AdminAreaInterface, AjaxComponentInterface
{
    protected $id = 'mediaLibrary';

    public function loadData(): void
    {
        $itemList = array_map([$this, 'buildMediaLibraryItem'], $this->getFilesList());

        $dataProv = new ObjectDataProvider(MediaLibraryItem::class);
        $dataProv->setData($itemList);
        $this->setDataProvider($dataProv);
    }

    public function loadHtml(): void
    {
        $uploadButton = new UploadImageButton();
        $uploadButton->setModal(new UploadImageModal());
        $this->addToToolbar( $uploadButton);

        $burger = new DropdownMenu();
        $burger->addItem(new RemoveAllButton());
        $this->addToToolbar($burger);
    }

    protected function getFilesList(): array
    {
        $libraryPath            = LibraryPathHelper::getPath();
        $availableExtensions    = FileExtensionsHelper::getAvailable();
        $galleryFileManager     = new GalleryFileManager($libraryPath, $availableExtensions);

        try
        {
            return $galleryFileManager->getUploadedFilesList();
        }
        catch (\Exception $exception)
        {
            throw new \Exception($this->translate($exception->getMessage()));
        }
    }

    protected function buildMediaLibraryItem(string $itemName): MediaLibraryItem
    {
        return (new MediaLibraryItem())
            ->onClick((new ModalLoad(new RemoveImageModal()))
                ->withParams(['fileName' => $itemName]))
            ->setImageUrl(ContentUrlGenerator::generateWithParams(["fileName" => $itemName]))
            ->setImageName($itemName);
    }
}
