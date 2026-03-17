<?php

use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Events\RemovedAllFilesFromGallery;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Events\RemovedFileFromGallery;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'menu' => [
        'admin' => [
            'appCenter' => [
                'icon' => 'widgets',
                'submenu' => [
                    'apps' => [],
                    'groups' => []
                ]
            ],
        ],
        'client' => [

        ],
    ],
    'controllers' => [
        'admin' => [
            AppCenter\Http\Admin\AppCenter::class,
        ],
        'client' => [
//            AppCenter\Http\Client\AppCenter::class,
        ],
    ],
    'packages' => [
        'MediaLibrary',
    ],
    'commands' => [
    ],
    'bootstrap' => function () {
        listen(ModuleActivated::class, AppCenter\Listeners\ModuleActivated::class);
        listen(RemovedAllFilesFromGallery::class, AppCenter\Listeners\RemovedAllFilesFromGallery::class);
        listen(RemovedFileFromGallery::class, AppCenter\Listeners\RemovedFileFromGallery::class);

//        $sideBar = (new ModulesGarden\OpenStackVpsCloud\Core\UI\Sidebar\Sidebar('appCenter'));
//
//        $url = WHMCS\URL\Client::productDetails(Request::get('id', 0), [
//                'modop' => 'custom',
//                'a' => 'management',
//                'mg-page' => 'backup',
//                'mg-action' => 'index'
//            ]);
//
//        $sideBar->addItem((new \ModulesGarden\OpenStackVpsCloud\Core\UI\Sidebar\Item(
//            'app_center',
//            $url,
//            1,
//        )));
//
//        \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Sidebar::addSidebar($sideBar);
    },
];
