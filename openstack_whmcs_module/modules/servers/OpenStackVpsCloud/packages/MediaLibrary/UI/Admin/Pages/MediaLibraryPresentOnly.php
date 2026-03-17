<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Pages;

class MediaLibraryPresentOnly extends MediaLibrary
{
    public function __construct()
    {
        parent::__construct();

        $this->setMode(self::MODE_PRESENT);
    }
}