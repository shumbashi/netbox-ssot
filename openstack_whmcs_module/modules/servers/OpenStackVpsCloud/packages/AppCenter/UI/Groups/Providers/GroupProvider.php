<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Groups\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Repositories\ItemsGroupsRepository;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;

class GroupProvider extends CrudProvider
{
    use TranslatorTrait;

    public function read()
    {
        parent::read();

        //TODO: fix properly
        $this->data->set('name', html_entity_decode(html_entity_decode($this->data->get('name', ''))));
        $this->data->set('description', html_entity_decode(html_entity_decode($this->data->get('description',  ''))));

        $groupId = $this->formData->get('id', false);
        if ($groupId) {
            $appItemsTable = (new Item())->getTable();
            $this->data->set('items', Group::findOrFail($groupId)->items()->pluck("$appItemsTable.id"));
        }

        $allItems = [];
        foreach (Item::all() as $item) {
            $type = (new AppTypesTranslator())->getTranslated($item->type);
            $allItems[$item->id] = sprintf('%s - %s', html_entity_decode($item->name ?: ''), $type);
        }

        natcasesort($allItems);

        $this->availableValues->set('items', $allItems);
    }

    public function create()
    {
        if (Group::where('name', $this->formData->get('name'))
            ->exists()) {
            return (new Response())
                ->setError($this->translate('already_exists'));
        }

        if (empty($this->formData->get('items')))
        {
            return (new Response())
                ->setError($this->translate('no_items'));
        }

        $group = new Group();
        $group->name = $this->formData->get('name');
        $group->description = $this->formData->get('description');
        $group->save();

        $group->items()
            ->sync($this->formData->get('items'));
    }

    public function update()
    {
        $groupId = $this->formData->get('id');

        if (empty($this->formData->get('items')))
        {
            return (new Response())
                ->setError($this->translate('no_items'));
        }

        Group::where('id', $groupId)
            ->update([
                'name' => $this->formData->get('name'),
                'description' => $this->formData->get('description')
            ]);

        Group::findOrFail($groupId)
            ->items()
            ->sync($this->formData->get('items'));
    }

    public function delete()
    {
        Group::findOrFail($this->formData->get('id'))
            ->delete();
    }
}