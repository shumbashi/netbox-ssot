<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers;

use Illuminate\Database\Capsule\Manager as DB;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppItemSource;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppItemActionMode;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\App\AppInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\AppGroup;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemsGroups;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Repositories\ItemsRepository;

class ItemRefreshProvider extends CrudProvider
{
    const ACTION_REFRESH = 'refresh';

    protected array $itemsUniqueConfigValues = [];

    public function read()
    {
        $this->data['action'] = AppItemActionMode::UPDATE_APPS;

        $this->availableValues['action'] = [
            AppItemActionMode::UPDATE_APPS   => $this->translate(AppItemActionMode::UPDATE_APPS),
            AppItemActionMode::DELETE_ALL    => $this->translate(AppItemActionMode::DELETE_ALL),
            AppItemActionMode::DELETE_LOADER => $this->translate(AppItemActionMode::DELETE_LOADER),
        ];
    }

    public function refresh()
    {
        $this->deleteItems();
        $this->loadItems();
    }

    protected function loadItems()
    {
        $apps = Config::get('appCenter.Apps', []);

        foreach ($apps as $app)
        {
            $appInstance = AppFactory::factory($app);
            $this->loadAppItems($appInstance);
        }

    }

    protected function loadAppItems(AppInterface $app)
    {
        $actionMode = $this->formData->get('action', false);

        foreach ($app->load() as $appModel)
        {
            switch ($actionMode)
            {
                case AppItemActionMode::UPDATE_APPS:
                    $this->findAndUpdate($appModel, $app);
                    break;
                default:
                    $this->createIfNotExists($appModel, $app);
                    break;
            }
        }
    }

    protected function deleteItems()
    {
        $actionMode = $this->formData->get('action', false);
        switch ($actionMode)
        {
            case AppItemActionMode::DELETE_ALL:
                ItemsRepository::delete();
                break;
            case AppItemActionMode::DELETE_LOADER:
                ItemsRepository::deleteLoaderItems();
                break;
            case AppItemActionMode::UPDATE_APPS:
            default:
                break;
        }
    }

    protected function findAndUpdate(App $appModel, AppInterface $app): void
    {
        $currentUniqueConfigValue = $appModel->getConfigArray()[$app->getUniqueConfigName()] ?? null;
        if ($currentUniqueConfigValue === null || !in_array($currentUniqueConfigValue, $this->getUniqueConfigValuesByApp($app)))
        {
            $this->createIfNotExists($appModel, $app);
            return;
        }

        $currentItemId = array_flip($this->getUniqueConfigValuesByApp($app))[$currentUniqueConfigValue];

        $staticConfig = $app->getStaticConfigDuringUpdate();

        foreach ($app->getDefaultConfig() as $config)
        {
            if (in_array($config->getSetting(), $staticConfig))
            {
                ItemConfig::firstOrCreate([
                    'item_id' => $currentItemId,
                    'setting' => $config->getSetting()
                ],
                    $config->toArray()
                );

                continue;
            }

            ItemConfig::updateOrCreate([
                'item_id' => $currentItemId,
                'setting' => $config->getSetting()
            ],
                $config->toArray()
            );
        }

        foreach ($appModel->getConfig() as $config)
        {
            $config->setLoader(null);

            if (in_array($config->getSetting(), $staticConfig))
            {
                ItemConfig::firstOrCreate([
                    'item_id' => $currentItemId,
                    'setting' => $config->getSetting()
                ],
                    $config->toArray()
                );

                continue;
            }

            ItemConfig::updateOrCreate([
                'item_id' => $currentItemId,
                'setting' => $config->getSetting()
            ],
                $config->toArray()
            );
        }
    }

    protected function createIfNotExists(App $appModel, AppInterface $app): void
    {
        if (Item::ofType($app::class)
            ->where('source', AppItemSource::SOURCE_LOADER)
            ->where('name', $appModel->getName())
            ->exists())
        {
            return;
        }

        $item = new Item();
        $item->fill($appModel->toArray());
        $item->type   = $app::class;
        $item->source = AppItemSource::SOURCE_LOADER;
        $item->save();

        if ($appModel->getGroup() instanceof AppGroup)
        {

            $group = Group::where('name', $appModel->getGroup()->getName())
                ->first();

            if (!$group)
            {
                $group              = new Group();
                $group->name        = $appModel->getGroup()->getName();
                $group->description = $appModel->getGroup()->getDescription();
                $group->save();
            }

            $itemGroup           = new ItemsGroups();
            $itemGroup->item_id  = $item->id;
            $itemGroup->group_id = $group->id;
            $itemGroup->save();
        }

        foreach ($app->getDefaultConfig() as $config)
        {
            $itemConfig = new ItemConfig();
            $itemConfig->fill($config->toArray());
            $itemConfig->item_id = $item->id;
            $itemConfig->save();
        }

        foreach ($appModel->getConfig() as $config)
        {
            $config->setLoader(null);

            ItemConfig::updateOrCreate([
                'item_id' => $item->id,
                'setting' => $config->getSetting()
            ],
                $config->toArray()
            );
        }
    }

    protected function getUniqueConfigValuesByApp(AppInterface $app): array
    {
        if (!isset($this->itemsUniqueConfigValues[$app::class]))
        {
            $ic = (new ItemConfig())->getTable();
            $i  = (new Item())->getTable();

            $currentAppItemsUniqueConfigValues = ItemConfig::join($i, "{$i}.id", "=", "{$ic}.item_id")
                ->where("{$i}.type", $app::class)
                ->where("{$ic}.setting", $app->getUniqueConfigName())
                ->get()
                ->pluck('value', 'item_id')
                ->toArray();

            $this->itemsUniqueConfigValues[$app::class] = $currentAppItemsUniqueConfigValues;
        }

        return $this->itemsUniqueConfigValues[$app::class];
    }
}