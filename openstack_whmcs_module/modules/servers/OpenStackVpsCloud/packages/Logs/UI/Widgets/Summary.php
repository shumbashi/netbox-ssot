<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Widgets;

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
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Enums\LogTypes;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Enums\Status;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Job;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Models\Logs;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\Enums\Colors;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container as DataContainer;
use WHMCS\Database\Capsule as DB;

class Summary extends ContainerRow implements AjaxComponentInterface, AdminAreaInterface
{
    protected const ORDERED_TYPES = [
        LogTypes::INFO,
        LogTypes::NOTICE,
        LogTypes::ALERT,
        LogTypes::DEBUG,
        LogTypes::WARNING,
        LogTypes::EMERGENCY,
        LogTypes::ERROR,
        LogTypes::CRITICAL,
        Colors::TOTAL_KEY,
    ];

    protected DataContainer $configContainer;

    protected string $cid = 'summaryLogsWidget';

    public function loadHtml(): void
    {
        //build per typy widgets
        $totalKey = Colors::TOTAL_KEY;
        $all      = Logs::select(DB::raw("'{$totalKey}' as type"), DB::raw('count(id) as count'));
        $types    = Logs::select('type', DB::raw('count(id) as count'))
            ->union($all)
            ->groupBy('type')
            ->get()
            ->sortBy(function($model) {
                return array_search($model->type, self::ORDERED_TYPES);
            });

        $elements = [];

        foreach ($types as $type)
        {
            $elements[] = [
                $this->buildCard(
                    $type->count,
                    $type->type,
                )];
        }

        $grid = new Grid();
        $grid->setRows([$elements]);
        (new Decorator($grid))->columns()->one();

        $this->addElement($grid);
    }

    protected function buildCard(int $count, $type):CardButton
    {
        $filterStatus = Arr::get(Request::get('ajaxData'), 'filters.type', Colors::TOTAL_KEY);

        $reloadParams = [
            "filters" => $type != Colors::TOTAL_KEY ? ['type' => $type] : []
        ];

        $button = $this->getButton($type);
        $button->setTitle($this->translate('show'));
        $button->onClick((new ReloadById("LogsDataTable"))->withParams($reloadParams));
        $button->onClick((new Reload($this))->withParams($reloadParams));


        $button->setDisabled(strtolower($filterStatus) == strtolower($type));

        $card = new CardButton();
        $card->addButton($button);
        $card->setIcon($this->getIcon($type));
        $card->setTitle($this->translate($type . 'Title'));
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