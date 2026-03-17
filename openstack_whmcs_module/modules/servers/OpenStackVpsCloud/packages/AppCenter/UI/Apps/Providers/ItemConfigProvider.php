<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\DataContainer;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppItemSource;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\FieldFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppConfigItemFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;

class ItemConfigProvider extends CrudProvider
{
    use TranslatorTrait;

    public function read()
    {
        parent::read();

        $id = $this->formData->get('id', Request::get('actionElementId'));
        if (!$id) {
            return;
        }

        $field = ItemConfig::findOrFail($id);
        $data = array_merge($field->toArray(), $this->data->toArray());
        $data['options'] = array_merge($data['options'] ?: [], $this->buildOptions($field));
        $data['visible'] = $field->visible;

        foreach ($data as $key => &$value) {
            if (!is_string($value)) {
                continue;
            }

            $value = html_entity_decode($value);
        }

        $this->data = new DataContainer($data);
    }

    public function create()
    {
        $itemId = Request::get('id', 0);
        if (ItemConfig::where('item_id', $itemId)
            ->where('setting', $this->formData->get('setting'))
            ->exists()) {
            return (new Response())
                ->setError($this->translate('setting_already_exists'));
        }

        $config = new ItemConfig;
        $config->fill($this->formData->toArray());
        $config->item_id = $itemId;
        $config->source = AppItemSource::SOURCE_MANUAL;
        $config->save();
    }

    public function update()
    {
        $config = ItemConfig::findOrFail($this->formData->get('id'));
        $config->value = null;
        $config->fill($this->formData->toArray());
        $config->save();
    }

    protected function buildOptions(ItemConfig $item)
    {
        $configItem = AppConfigItemFactory::forItem($item);

        $options = $this->formData['options'] ?: [];

        $isProperLoader = empty($this->data['field']) || $this->data['field'] == $configItem->getField();

        $configField = FieldFactory::forItem($configItem, $isProperLoader);
        foreach ($configField->getSlots() as $slot => $value) {
            if (isset($options[$slot]) || empty($value)) {
                continue;
            }

            if ($slot === 'options') {
                $options[$slot] = array_map(fn($opt) => $opt['name'], $value);
            } else {
                $setter = "set" . ucfirst($slot);
                if (is_callable([$configField, $setter])) {
                    $options[$slot] = $value;
                }
            }
        }

        return $options;
    }
}