<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Containers;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\Services\LangsManager;

class AlertContainer extends Container implements AdminAreaInterface, AjaxOnLoadInterface
{
    const ALERT_CONTAINER_ID = 'alertContainer';

    public function loadHtml(): void
    {
        $this->setId(self::ALERT_CONTAINER_ID);

        $language = Request::get('language', "");
        $service = new LangsManager();

        if (empty($service->getMissingLangs($language))) {
            $this->clearElements();
        } else {
            $alert = new AlertDanger();
            $alert->setOutline();
            $alert->setText($this->translate('incompleteLanguageInfo', ['languageName' => ucfirst($language)]));
            $this->addElement($alert);
        }
    }
}