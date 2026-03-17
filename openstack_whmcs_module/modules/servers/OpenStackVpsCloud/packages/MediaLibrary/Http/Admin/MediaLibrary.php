<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Http\Admin;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Helper;
use ModulesGarden\OpenStackVpsCloud\Core\Http\AbstractController;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\UI\Admin\Pages\MediaLibrary as MediaLibraryComponent;

class MediaLibrary extends AbstractController implements AdminAreaInterface
{
    /**
     * Example of static page
     * @return View
     */
    public function index()
    {
        return Helper\view()->addElement(MediaLibraryComponent::class);
    }
}