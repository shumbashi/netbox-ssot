<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Models\Lang;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Containers\AlertContainer;

class DeleteLangProvider extends CrudProvider
{
    use TranslatorTrait;

    public function delete()
    {
        Lang::where('id', $this->formData->get('id'))->delete();

        return (new Response())
            ->setActions([Action::reloadById(AlertContainer::ALERT_CONTAINER_ID), Action::reloadParent()])
            ->setSuccess($this->translate('delete_success'));
    }
}