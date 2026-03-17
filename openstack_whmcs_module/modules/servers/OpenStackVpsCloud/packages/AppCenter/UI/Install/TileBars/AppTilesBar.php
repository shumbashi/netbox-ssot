<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\TileBars;

use ModulesGarden\OpenStackVpsCloud\Components\TileButton\TileButton;
use ModulesGarden\OpenStackVpsCloud\Components\TilesBar\TilesBar;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalLoad;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppStatus;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services\AppConfiguration;
use ModulesGarden\OpenStackVpsCloud\Packages\MediaLibrary\Helpers\ContentUrlGenerator;

class AppTilesBar extends TilesBar implements AjaxComponentInterface
{
    protected ?string $app = null;

    public function __construct(?string $app)
    {
        $this->app = $app;

        parent::__construct();
    }

    public function loadHtml(): void
    {
        $this->addAppTiles();
    }

    protected function addAppTiles()
    {
        $groupIds = (new AppConfiguration(Params::get('serviceid')))->getAppGroupIds();
        if (!$groupIds) {
            return;
        }

        $g = (new Group())->getTable();
        $items = Item::whereHas('groups', static function ($query) use ($g, $groupIds)
        {
            $query->whereIn("{$g}.id", $groupIds);
        })
            ->where('status', AppStatus::STATUS_ACTIVE)
            ->ofType($this->app)
            ->active()
            ->orderBy('name', 'ASC')
            ->get();

        foreach ($items as $item) {

            $tile = new TileButton();
            $tile->setTitle(html_entity_decode($item->name));

            if (!empty($item->image)) {
                $image = ContentUrlGenerator::generateWithParams(['fileName' => $item->image]);
                $tile->setImageUrl($image);
            }

            $app = AppFactory::factory($this->app);

            $tile->onClick((new ModalLoad($app->getInstallModal()))
                ->withParams(['id' => $item->id]));

            $this->addTile($tile);
        }
    }
}