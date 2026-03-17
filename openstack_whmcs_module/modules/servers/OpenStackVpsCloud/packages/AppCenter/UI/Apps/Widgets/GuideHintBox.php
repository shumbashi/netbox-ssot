<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Hint\Hint;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Fragments\ConfigurableHintBox\UI\Widgets\ConfigurableHintBox;

class GuideHintBox extends ConfigurableHintBox implements AdminAreaInterface
{
    public function preLoadHtml(): void
    {
        $this->setId('guide_hint_box');
        parent::preLoadHtml();
    }

    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));

        $hintWelcome = new Hint();
        $hintWelcome->setTitle($this->translate('introduction_title'));
        $hintWelcome->setDescription($this->translate('introduction'));

        $synchronization = new Hint();
        $synchronization->setTitle($this->translate("synchronization_title"));
        $synchronization->setDescription($this->translate("synchronization_description"));

        $groups = new Hint();
        $groups->setTitle($this->translate("groups_title"));
        $groups->setDescription($this->translate("groups_description"));

        $customization = new Hint();
        $customization->setTitle($this->translate("customization_title"));
        $customization->setDescription($this->translate("customization_description"));

        $advancedConfiguration = new Hint();
        $advancedConfiguration->setTitle($this->translate("configuration_title"));
        $advancedConfiguration->setDescription($this->translate("configuration_description"));

        $this->addHint($hintWelcome);
        $this->addHint($synchronization);
        $this->addHint($groups);
        $this->addHint($customization);
        $this->addHint($advancedConfiguration);
    }
}