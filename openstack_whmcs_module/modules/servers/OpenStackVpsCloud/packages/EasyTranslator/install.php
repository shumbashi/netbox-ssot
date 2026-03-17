<?php

use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Listeners\ModuleActivated;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Listeners\ModuleUpgraded;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Listeners\MissingTranslationDetected;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Repositories\Langs;
use function ModulesGarden\OpenStackVpsCloud\Core\listen;

return [
    'menu'        => [
        'admin'  => [
            'easyTranslator' => [
                'icon' => 'translate',
            ],
        ],
        'client' => [

        ],
    ],
    'controllers' => [
        'admin'  => [
            \ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Http\Admin\EasyTranslator::class,
        ],
        'client' => [

        ],
    ],
    'packages'    => [
        'ModuleSettings'
    ],
    'bootstrap' => function() {
        \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator::addLoader('dynamics', new \ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Loader\DynamicTranslations());
        \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator::addLoader('database', new \ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Loader\Database());

        try {
            foreach (Langs::getUsedLangs() as $locale) {
                \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator::addResource("database", '', $locale);
                \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator::addResource("dynamics", '', $locale);
            }
        } catch (\Exception $ex) {
            //Do Nothing TODO change to event
        }

        listen(\ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleActivated::class, ModuleActivated::class);
        listen(\ModulesGarden\OpenStackVpsCloud\Core\Events\Events\ModuleUpgraded::class, ModuleUpgraded::class);
        listen(\ModulesGarden\OpenStackVpsCloud\Core\Events\Events\MissingTranslationDetected::class, MissingTranslationDetected::class);
    },
];
