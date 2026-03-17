<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\Elements\UploadModal;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\FileExtensionsHelper;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Forms\UploadImageForm;

class UploadImageModal extends UploadModal implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));

        $this->addElement($this->buildInfoAlert());
        $this->addElement($this->buildUploadForm());
    }

    protected function buildInfoAlert():AlertInfo
    {
        if (!function_exists("getIniSettingSize"))
        {
            require ROOTDIR . "/includes/functions.php";
        }

        return (new AlertInfo())->setText($this->translate('uploadImageInfo', [
            'maxUploadSize'     => getIniSettingSize('upload_max_filesize', "MB"),
            'allowedExtensions' => implode(", ", array_map("strtoupper", FileExtensionsHelper::getAvailable()))
        ]));
    }

    protected function buildUploadForm():UploadImageForm
    {
        return new UploadImageForm();
    }
}