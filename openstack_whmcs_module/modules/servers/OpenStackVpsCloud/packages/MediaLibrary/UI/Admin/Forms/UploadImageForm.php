<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\UploadField\UploadField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\FileExtensionsHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Providers\UploadProvider;

class UploadImageForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = UploadProvider::class;
    protected string $providerAction = 'upload';

    public function loadHtml(): void
    {
        $this->builder->createField(UploadField::class, 'uploadFileField')
            ->setAllowedFileTypes($this->getAcceptedImageTypes());
    }

    protected function getAcceptedImageTypes(): array
    {
        return array_map(function ($value) {
            return "image/{$value}";
        }, FileExtensionsHelper::getAvailable());
    }
}