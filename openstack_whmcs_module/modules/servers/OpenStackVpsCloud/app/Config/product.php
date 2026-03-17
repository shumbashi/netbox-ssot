<?php

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL;

return [
    'ServerConfigForm' => \ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ServerConfig\Forms\ServerConfigForm::class,
    'sidebars' => [
        'openstackVpsCloudManagement' => [
            'reinstallation' => [
                'order' => 7,
                'uri' => function () {
                    return URL\Client::productDetails(Request::get('id', 0), [
                        'modop' => 'custom',
                        'a' => 'management',
                        'mg-page' => 'reinstallation',
                        'mg-action' => 'index'
                    ]);
                },
                'enabled' => false
            ],
            'backups' => [
                'order' => 4,
                'uri' => function () {
                    return URL\Client::productDetails(Request::get('id', 0), [
                        'modop' => 'custom',
                        'a' => 'management',
                        'mg-page' => 'backups',
                        'mg-action' => 'index'
                    ]);
                },
                'enabled' => false
            ],
            'console' => [
                'order' => 5,
                'uri' => function () {
                    return URL\Client::productDetails(Request::get('id', 0), [
                        'modop' => 'custom',
                        'a' => 'management',
                        'mg-page' => 'console',
                        'mg-action' => 'index'
                    ]);
                },
                'enabled' => false
            ],
            'firewall' => [
                'order' => 6,
                'uri' => function () {
                    return URL\Client::productDetails(Request::get('id', 0), [
                        'modop' => 'custom',
                        'a' => 'management',
                        'mg-page' => 'firewall',
                        'mg-action' => 'index'
                    ]);
                },
                'enabled' => false
            ],
            'snapshots' => [
                'order' => 8,
                'uri' => function () {
                    return URL\Client::productDetails(Request::get('id', 0), [
                        'modop' => 'custom',
                        'a' => 'management',
                        'mg-page' => 'snapshots',
                        'mg-action' => 'index'
                    ]);
                },
                'enabled' => false
            ],
        ]
    ],
    'CustomFields' => [
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\CustomFields\TextBox('vmID', 'VM ID'))
            ->setAdminOnly(true),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\CustomFields\TextBox('privateFlavor', 'Private Flavor ID'))
            ->setAdminOnly(true),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\CustomFields\TextArea('sshKey', 'SSH Public Key'))
            ->setRegExpr('#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#')
            ->setDescription("Enter your public key in the OpenSSH format here (e.g. ssh-rsa).")
            ->setShowOnOrder(true),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\CustomFields\TextBox('domain', 'Domain'))
            ->setShowOnOrder(true)
            ->setDescription("Enter the domain on which the VM will be created."),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\CustomFields\TextBox('firewallSecurityGroupID', 'Firewall Security Group ID'))
            ->setAdminOnly(true)
    ],
    'ConfigurableOptions' => [
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('flavor', 'Flavor'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $server = \ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository::findByProductId($product->id);

                Factory::adminFromServerId($server->id);
                $flavors = Api::getInstance()->compute()->listFlavors();
                foreach ($flavors as $flavor) {
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($flavor['name'], $flavor['name']));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('ips', 'IP Addresses'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', '1'));
            })
            ->setRange(0),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('volume_size', 'Volume Size'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', 'GB'));
            })
            ->setRange(0),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('backupsFilesLimit', 'Backups Files Limit'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', '1'));
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('snapshotsFilesLimit', 'Snapshots Files Limit'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', '1'));
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('volume_type', 'Volume Type'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $server = \ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository::findByProductId($product->id);
                Factory::adminFromServerId($server->id);

                $blockDevicesTypes = Api::getInstance()->volume()->getTypes();
                foreach ($blockDevicesTypes as $device) {
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($device['name'], $device['name']));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('disk', 'Disk'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $diskSizes = [5, 10, 20, 30];
                foreach ($diskSizes as $diskSize) {
                    $friendlyName = "$diskSize [GB]";
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($diskSize, $friendlyName));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('ram', 'RAM'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $memorySize = [512, 1024, 2048, 3072];
                foreach ($memorySize as $size) {
                    $friendlyName = "$size [MB]";
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($size, $friendlyName));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('vcpus', 'VCPUs'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $cpus = [1, 2, 3, 4];
                foreach ($cpus as $i) {
                    $friendlyName = "$i";
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($i, $friendlyName));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('totalRulesLimit', 'Total Firewall Rules Limit'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', '1'));
            })
            ->setRange(0),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('inboundRulesLimit', 'Inbound Firewall Rules Limit'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', '1'));
            })
            ->setRange(0),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity('outboundRulesLimit', 'Outbound Firewall Rules Limit'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Quantity $quantity) {
                $quantity->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption('1', '1'));
            })
            ->setRange(0),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('region', 'Region'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $server = \ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository::findByProductId($product->id);
                $tenant = Factory::adminFromServerId($server->id);
                $tenant->setProductId($product->id);
                $regions = $tenant->api()->getApIdentity()->getRegions();
                foreach ($regions as $name => $friendlyName) {
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($name, $friendlyName));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('availabilityZone', 'Availability Zone'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {
                $server = \ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository::findByProductId($product->id);
                Factory::adminFromServerId($server->id);

                $zones = [
                    0 => [
                        'zoneName' => 'auto'
                    ]
                ];

                $zones = array_merge($zones, Api::getInstance()->compute()->getAvailableAvailabilityZone());

                foreach ($zones as $zone) {
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($zone['zoneName'], $zone['zoneName']));
                }
            }),
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown('app', 'Application'))
            ->setOptionsLoader(function (\ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\Dropdown $dropdown, \ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product $product) {

                $productConfig = new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration($product->id);
                $groupIds = $productConfig->get()[\ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppProductConfig::GROUP_DROPDOWN_NAME];
                $g = (new \ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group())->getTable();
                $items = \ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item::whereHas('groups', static function ($query) use ($g, $groupIds)
                {
                    $query->whereIn("{$g}.id", $groupIds);
                })->get();

                foreach ($items as $item) {
                    $dropdown->addOption(new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Libs\ConfigurableOptions\SubOption\SubOption($item->id, $item->name));
                }
            })
    ]
];