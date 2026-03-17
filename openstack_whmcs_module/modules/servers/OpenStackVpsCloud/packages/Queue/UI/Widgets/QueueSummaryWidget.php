<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Button\Button;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonBasic;
use ModulesGarden\OpenStackVpsCloud\Components\Card\CardButton;
use ModulesGarden\OpenStackVpsCloud\Components\Container\ContainerRow;
use ModulesGarden\OpenStackVpsCloud\Components\Grid\Grid;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Decorator\Decorator;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Colors;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Models\Job;
use WHMCS\Database\Capsule as DB;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container as DataContainer;

class QueueSummaryWidget extends ContainerRow implements AjaxComponentInterface, AdminAreaInterface
{
    protected const ORDERED_STATUSES = [
        Status::PENDING,
        Status::WAITING,
        Status::RUNNING,
        Status::CANCELLED,
        Status::ERROR,
        Status::FAILED,
        Status::FINISHED,
        Colors::TOTAL_KEY,
    ];

    protected DataContainer $configContainer;

    public function loadHtml(): void
    {
        //build per typy widgets
        $totalKey = Colors::TOTAL_KEY;
        $all      = Job::select(DB::raw("'{$totalKey}' as status"), DB::raw('count(id) as count'));
        $types    = Job::select('status', DB::raw('count(id) as count'))
            ->union($all)
            ->groupBy('status')
            ->get()
            ->sortBy(function($model) {
                return array_search($model->status, self::ORDERED_STATUSES);
            });

        foreach ($types as $type)
        {
            $elements[] = [
                $this->buildCard(
                    $type->count,
                    $type->status,
                )];
        };

        $grid = new Grid();
        $grid->setRows([$elements]);
        (new Decorator($grid))->columns()->one();

        $this->addElement($grid);
    }

    protected function buildCard(int $count, $type)
    {
        $filterStatus = Arr::get(Request::get('ajaxData'), 'filters.status', Colors::TOTAL_KEY);

        $reloadParams = [
            "filters" => $type != Colors::TOTAL_KEY ? ['status' => $type] : []
        ];

        $button = $this->getButton($type);
        $button->setTitle($this->translate('show'));
        $button->onClick((new ReloadById("queueDataTable"))->withParams($reloadParams))
            ->onClick((new Reload($this))->withParams($reloadParams));

        $button->setDisabled(strtolower($filterStatus) == strtolower($type));

        $card = new CardButton();
        $card->addButton($button);
        $card->setIcon($this->getIcon($type));
        $card->setTitle($this->translate($type . 'Title'));
        $card->setDescription($this->translate($type . 'Title_description'));
        $card->setContent($count);

        return $card;
    }

    protected function getButton($type):Button
    {
        return new ($this->getConfiguration()->get( $type . '.button', ButtonBasic::class));
    }

    protected function getIcon($type):string
    {
        return $this->getConfiguration()->get( $type . '.icon', '');
    }

    protected function getConfiguration():DataContainer
    {
        if (!isset($this->configContainer))
        {
            $this->configContainer = new DataContainer(Colors::all());
        }

        return $this->configContainer;
    }
}