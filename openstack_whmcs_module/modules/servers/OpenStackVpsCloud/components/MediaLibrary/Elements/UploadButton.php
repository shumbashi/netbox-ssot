<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\Elements;

use ModulesGarden\OpenStackVpsCloud\Components\Button\Button;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;

abstract class UploadButton extends Button
{
    protected $css = 'lu-btn';

    public function __construct()
    {
        parent::__construct();
        $this->setTitle($this->translate('upload_image'));
        $this->setCss('lu-btn lu-btn--primary');
    }

    public function setModal(UploadModal $modal)
    {
        $this->onClick(Action::modalOpen($modal));
    }
}
