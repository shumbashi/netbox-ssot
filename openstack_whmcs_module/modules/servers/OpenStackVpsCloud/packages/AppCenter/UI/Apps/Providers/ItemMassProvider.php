<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms\MassEditItemForm;

class ItemMassProvider extends CrudProvider
{
    protected array $integrityCheckFields = [];

    public function delete()
    {
        $ids = explode(',', $this->formData['id']);
        Item::whereIn('id', $ids)->get()->each(function ($item) {
            $item->delete();
        });
    }

    public function update()
    {
        $ids = explode(',', $this->formData['id']);
        $updated = $this->formData->toArray();
        unset($updated['id']);

        //leave unfilled values unchanged
        foreach ($updated as $key => $value)
        {
            if (empty($value) || $value == MassEditItemForm::NO_CHANGE) {
                unset($updated[$key]);
            }
        }

        if (empty($updated))
        {
            return;
        }

        Item::whereIn('id', $ids)
            ->update($updated);
    }
}