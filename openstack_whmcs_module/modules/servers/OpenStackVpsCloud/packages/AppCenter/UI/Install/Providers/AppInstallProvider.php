<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Validator;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppStatus;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories\AppFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppModelFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Group;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Services\AppConfiguration;

class AppInstallProvider extends CrudProvider
{
    use TranslatorTrait;

    public function read()
    {
        parent::read();

        $itemId   = $this->formData->get('id');
        $groupIds = (new AppConfiguration(Params::get('serviceid')))->getAppGroupIds();

        $g = (new Group())->getTable();
        $item = Item::whereHas('groups', static function ($query) use ($g, $groupIds)
        {
            $query->whereIn("{$g}.id", $groupIds);
        })
            ->where('status', AppStatus::STATUS_ACTIVE)
            ->findOrFail($itemId);

        $appModel = AppModelFactory::forServiceItem(Service::findOrFail(Params::get('serviceid')), $item);

        foreach ($appModel->getConfig() as $configField)
        {
            if (!$configField->getVisible())
            {
                continue;
            }

            if (is_array($configField->getValue()))
            {
                $values = $configField->getValue();
                foreach ($values as &$value)
                {
                    $value = html_entity_decode($value);
                }

                $this->data[$configField->getSetting()] = $values;
            }
            else
            {
                $this->data[$configField->getSetting()] = html_entity_decode($configField->getValue());
            }
        }
    }

    public function create()
    {
        $itemId = $this->formData->get('id');
        $this->formData->delete('id');

        $groupIds = (new AppConfiguration(Params::get('serviceid')))->getAppGroupIds();

        $g = (new Group())->getTable();
        $item = Item::whereHas('groups', static function ($query) use ($g, $groupIds)
        {
            $query->whereIn("{$g}.id", $groupIds);
        })
            ->where('status', AppStatus::STATUS_ACTIVE)
            ->findOrFail($itemId);

        $appModel = AppModelFactory::forServiceItem(
            Service::findOrFail(Params::get('serviceid')),
            $item, $this->formData->toArray());

        $app = AppFactory::factory($item->type);

        $validators = [];

        $appModelConfig = $appModel->getConfig();
        foreach ($appModelConfig as $configField)
        {
            if (!$configField->getValidator())
            {
                continue;
            }

            $validators[$configField->getSetting()] = implode('|', $configField->getValidator());
        }

        Validator::validate($this->formData->toArray(), $validators);

        $result = $app->install($this->formData, Params::all(), $appModel);

        if ($result === true)
        {
            return;
        }

        if ($result instanceof Response)
        {
            return $result;
        }

        return (new Response())
            ->setError($this->translate("install_failed"));
    }
}