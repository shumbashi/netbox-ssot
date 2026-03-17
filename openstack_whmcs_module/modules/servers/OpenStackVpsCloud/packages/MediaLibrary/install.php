<?php

return [
    'menu'        => [
        'admin'  => [
            'mediaLibrary' => [
                'icon' => 'image-multiple',
            ],
        ],
        'client' => [

        ],
    ],
    'controllers' => [
        'admin'  => [
            \ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Http\Admin\MediaLibrary::class,
        ],
        'client' => [

        ],
    ],
    'bootstrap'   => function() {
    },
];