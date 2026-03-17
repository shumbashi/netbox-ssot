<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\Languages\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsManager;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Containers\AlertContainer;

class RefreshLanguageProvider extends CrudProvider
{
    use TranslatorTrait;

    public function read()
    {
        $language = $this->formData->get('originalLanguage');
        $service  = new LangsManager();

        $missingLangs = $service->getMissingLangs($language);

        $this->data->set('language', $language);
        $this->data->set('missingLangs', $missingLangs);
        $this->data->set('missingLangsCount', count($missingLangs));
    }

    public function create()
    {
        $service = new LangsManager();
        $service->updateMissingLangs($this->formData->get('language'));

        return (new Response())
            ->setActions([Action::reloadById(AlertContainer::ALERT_CONTAINER_ID), Action::reloadParent()])
            ->setSuccess($this->translate('create_success'));
    }
}