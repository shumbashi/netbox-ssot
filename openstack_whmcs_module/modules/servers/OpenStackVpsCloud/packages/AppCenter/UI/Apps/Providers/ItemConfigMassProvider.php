<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\ItemConfig;

class ItemConfigMassProvider extends CrudProvider
{
    protected array $integrityCheckFields = [];

    public function delete()
    {
        $ids = explode(',', $this->formData['id']);

        if (ItemConfig::whereIn('id', $ids)
            ->where('protected', 1)
            ->exists()) {

            return (new Response())
                ->setError($this->translate('unable_to_delete'));
        }

        ItemConfig::whereIn('id', $ids)->get()->each(function ($itemConfig) {
            $itemConfig->delete();
        });
    }

    public function update()
    {
        $ids = explode(',', $this->formData['id']);
        $updated = $this->formData->toArray();
        unset($updated['id']);

        ItemConfig::whereIn('id', $ids)
            ->update($updated);
    }
}