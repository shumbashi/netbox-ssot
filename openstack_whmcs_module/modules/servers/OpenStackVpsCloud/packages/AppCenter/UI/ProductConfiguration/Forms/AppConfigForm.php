<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\ProductConfiguration\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertWarning;
use ModulesGarden\OpenStackVpsCloud\Components\Div\Div;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Grid\Grid;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppProductConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;

class AppConfigForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $applications = $this->getApplications();
        $groups = array_map('html_entity_decode', Group::pluck('name', 'id')->toArray());
        natcasesort($groups);

        if(empty($applications) || empty($groups))
        {
            $this->addElement((new AlertWarning())->setText($this->translate("no_application_or_group_detected")));
        }

        $this->setContainerTag('div');

        $leftSection = new Div();
        $rightSection = new Div();

        $this->builder->addFieldInContainer($leftSection, (new Dropdown())
            ->setMultiple()
            ->setOptions($groups)
            ->setName(sprintf('customconfigoption[%s]', AppProductConfig::GROUP_DROPDOWN_NAME)),
            true);

        $this->builder->addFieldInContainer($rightSection, (new Dropdown())
            ->setOptions($applications)
            ->setGroups($this->getApplicationGroups())
            ->setName(sprintf('customconfigoption[%s]', AppProductConfig::APP_DROPDOWN_NAME)),
            true);

        $grid = new Grid();
        $grid->setRows([[
            [$leftSection, 6], [$rightSection, 6]
        ]]);

        $this->addElement($grid);
    }

    private function getApplicationGroups() : array
    {
        $groups = [];
        foreach (Item::select('type')->distinct()->get() as $group)
        {
            $groups[$group->type] = (new AppTypesTranslator())->getTranslated($group->type);
        }
        return $groups;
    }

    private function getApplications() : array
    {
        $applications = Item::selectRaw('type as `group`, name, id as `value`')
            ->orderBy('name', 'ASC')
            ->get()
            ->toArray();

        foreach ($applications as &$application)
        {
            $name = sprintf('#%s %s', $application['value'], html_entity_decode($application['name']));
            $application['name'] = $name;
            $application['search'] = $name;
        }

        return $applications;
    }
}