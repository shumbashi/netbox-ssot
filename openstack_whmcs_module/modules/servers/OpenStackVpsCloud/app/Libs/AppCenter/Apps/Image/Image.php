<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\AppCenter\Apps\Image;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RebuildingVolume;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\SendEmail;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ServerConfig;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\CustomScriptBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\ImageBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders\MetadataBuilder;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ProtectionVmManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\VmService;
use ModulesGarden\OpenStackVpsCloud\App\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Server;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppStatus;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\App\BaseApp;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\ProtectedAppConfigItem;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Services\Logs;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Support\Facades\Logger;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class Image extends BaseApp
{
    const APP_SETTING_UUID = 'UUID';
    const APP_SETTING_USERNAME = 'username';
    const APP_SETTING_USER_DATA = 'user_data';
    const APP_SETTING_METADATA = 'metadata';
    const APP_SETTING_NAME = 'name';

    const FIELDS = [
        self::APP_SETTING_UUID,
        self::APP_SETTING_USERNAME,
        self::APP_SETTING_USER_DATA,
        self::APP_SETTING_METADATA,
        self::APP_SETTING_NAME,
    ];

    public function load(): array
    {
        $servers = Server::where('type', ModuleConstants::getModuleName())->get();

        $apps = [];
        foreach ($servers as $server) {
            try {
                (new ServerConfig())->refreshServerResources($server->id);
                $tenant = Factory::adminFromServerId($server->id);

                foreach ($tenant->listImages() as $image) {
                    $config = [];

                    $key = $image->getName();
                    if (empty($key)) {
                        $key = $image->getUUID();
                    }

                    if (array_key_exists($key, $apps)) {
                        continue;
                    }

                    foreach ($image->toArray() as $attr => $value) {
                        if (!in_array($attr, self::FIELDS)) {
                            continue;
                        }

                        $item = new ProtectedAppConfigItem($attr, $value);

                        switch ($attr)
                        {
                            case self::APP_SETTING_NAME:
                                $item->setDescription($this->translate('name_description'));
                                break;
                            case self::APP_SETTING_UUID:
                                $item->setDescription($this->translate('uuid_description'));
                                break;
                            default:
                        }

                        $config[] = $item;
                    }

                    $app = (new \ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App())
                        ->setName(sprintf("%s", $image->getName()))
                        ->setDescription(sprintf('Image %s', $image->getName()))
                        ->setStatus(AppStatus::STATUS_ACTIVE)
                        ->setConfig($config);

                    $apps[$key] = $app;
                }
            } catch (\Throwable $t) {
                (new Logs())->error(\ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\LoggerMessages::LIST_IMAGES_FAILED, [
                    'serverid' => $server->id,
                    'message' => $t->getMessage(),
                ]);
            }
        }

        return array_values($apps);
    }

    public function install(Container $formData, array $params, App $app)
    {
        if (JobManager::isBuilding($params['serviceid'])) {
            throw new \Exception('already_building');
        }

        if (JobManager::isRebuiling($params['serviceid'])) {
            throw new \Exception('already_rebuilding');
        }

        try {
            $tenant = Factory::getTenantFromServiceId($params['serviceid']);
            $productConfiguration = (new ProductConfiguration($params['packageid']))->get();

            $appConfig = $app->getConfigArray();

            $imageBuilder = new ImageBuilder($params);
            $image = $imageBuilder->buildFromApp($app);

            $protectVmManager = new ProtectionVmManager($params['serviceid']);
            $error = $protectVmManager->getStatus();
            if ($error) {
                throw new \Exception('vm_protected');
            }

            $userData = $appConfig['user_data'];
            $metadata = $appConfig['metadata'];

            $customScript = (new CustomScriptBuilder($params))
                ->build($userData);

            $vmService = new VmService($params);
            $vm = $vmService->getVm();

            if ($productConfiguration['use_volumes']) {

                Job::byServiceID($params['serviceid'])
                    ->whereIn('status', [Status::WAITING, Status::PENDING, Status::RUNNING, Status::ERROR, ''])
                    ->update(['status' => Status::CANCELLED]);

                Queue::push(RebuildingVolume::class,
                    [
                        'hid' => $params['serviceid'],
                        'pid' => $params['pid'],
                        'data' =>
                            [
                                'newImageId' => $image->getUUID(),
                                'user_data' => $customScript,
                                'metadata' => $metadata
                            ]
                    ],
                    'default',
                    null,
                    'Hosting',
                    $params['serviceid']);

                Logger::info(LoggerMessages::INSTANCE_REINSTALL_SUCCESS, [
                    'service' => Params::get('serviceid'),
                ]);

                return true;
            }

            $vm->setImage($image);

            if (empty($vm->getUUID())) {
                throw new \Exception('Invalid image format, UUID cannot be empty.');
            }

            Job::byServiceID($params['serviceid'])
                ->whereIn('status', [Status::WAITING, Status::PENDING, Status::RUNNING, Status::ERROR, ''])
                ->update(['status' => Status::CANCELLED]);

            Api::getInstance()->compute()->rebuild(
                $vm->getUUID(),
                $vm->getName(),
                $params['password'],
                $vm->getImage()->getUUID(),
                $customScript,
            );

            $vm->setDetails();

            Queue::push(SendEmail::class,
                [
                    'hid' => $params['serviceid'],
                    'pid' => $params['pid'],
                ],
                'default',
                null,
                'Hosting',
                $params['serviceid']
            );

            $metaDataBuilder = (new MetadataBuilder());
            $metaDataArray = $metaDataBuilder->build($metadata);

            if ($vm->getStatus() != VPSModel::STATUS_ERROR && !empty($metaDataArray)) {
                Api::getInstance()->compute()->updateMetadata($vm->getUUID(), $metaDataArray);
            }

            Logger::info(LoggerMessages::INSTANCE_REINSTALL_SUCCESS, [
                'service' => Params::get('serviceid'),
            ]);

        } catch (\Exception $e) {
            Logger::error(LoggerMessages::INSTANCE_REINSTALL_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $formData->toArray()
            ]);
            throw $e;
        } catch (\Throwable $e) {
            Logger::critical(LoggerMessages::INSTANCE_REINSTALL_FAILED, [
                'service' => Params::get('serviceid'),
                'message' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
                'data' => $formData->toArray()
            ]);
            throw $e;
        }

        return true;
    }

    public function getUniqueConfigName(): string
    {
        return self::APP_SETTING_NAME;
    }

    public function getDefaultConfig(): array
    {
        return [
            (new ProtectedAppConfigItem(self::APP_SETTING_NAME, ''))
                ->setDescription($this->translate('name_description')),
            (new ProtectedAppConfigItem(self::APP_SETTING_UUID, ''))
                ->setDescription($this->translate('uuid_description')),
            (new ProtectedAppConfigItem(self::APP_SETTING_USERNAME, ''))
                ->setDescription($this->translate('username_description')),
            (new ProtectedAppConfigItem(self::APP_SETTING_USER_DATA, ''))
                ->setDescription($this->translate('user_data_description')),
            (new ProtectedAppConfigItem(self::APP_SETTING_METADATA, ''))
                ->setDescription($this->translate('metadata_description')),
        ];
    }

    public function getStaticConfigDuringUpdate(): array
    {
        return [
            self::APP_SETTING_NAME,
            self::APP_SETTING_UUID,
            self::APP_SETTING_USERNAME,
            self::APP_SETTING_USER_DATA,
            self::APP_SETTING_METADATA,
        ];
    }
}