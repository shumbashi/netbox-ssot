<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\DataContainer;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppItemSource;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support\AppTypesTranslator;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Validator;

class ItemProvider extends CrudProvider
{
    use TranslatorTrait;

    const ACTION_DUPLICATE = 'duplicate';

    public function read()
    {
        parent::read();

        if ($itemId = Request::get('id', $this->formData->get('id')))
        {
            $data = Item::findOrFail($itemId)->toArray();
            foreach ($data as $key => &$value)
            {
                if (!is_string($value)) {
                    continue;
                }

                $value = html_entity_decode($value);
            }

            $this->data = new DataContainer($data);
        }

        $this->availableValues['type'] = (new AppTypesTranslator())->getAvailableTranslated();
    }

    public function update()
    {
        Item::where('id', Request::get('id'))
            ->update([
                'name' => $this->formData->get('name'),
                'description' => $this->formData->get('description'),
                'image' => $this->formData->get('image'),
                'status' => $this->formData->get('status')
            ]);
    }

    public function delete()
    {
        Item::where('id', $this->formData->get('id'))->delete();
    }

    public function create()
    {
        $item = new Item();
        $item->type = $this->formData->get('type');
        $item->name = html_entity_decode($this->formData->get('name', ''));
        $item->description = $this->formData->get('description');
        $item->image = $this->formData->get('image');
        $item->source = AppItemSource::SOURCE_MANUAL;
        $item->status = $this->formData->get('status');
        $item->save();

        $app = AppFactory::factory($item->type);

        foreach ($app->getDefaultConfig() as $config)
        {
            $itemConfig = new ItemConfig();
            $itemConfig->fill($config->toArray());
            $itemConfig->item_id = $item->id;
            $itemConfig->save();
        }
    }

    public function duplicate()
    {
        Validator::validate($this->formData->toArray(), [
            'name' => 'required',
            'id' => 'required',
        ]);

        $item = Item::findOrFail($this->formData->get('id'));

        $newItem = $item->replicate();
        $newItem->name = $this->formData->get('name');

        $newItem->push();

        foreach ($item->itemConfig as $config) {
            $newConfig = $config->replicate();
            $newConfig->item_id = $newItem->id;
            $newConfig->save();
        }

        foreach ($item->groups as $group) {
            $newItem->groups()->attach($group->id);
        }
    }
}