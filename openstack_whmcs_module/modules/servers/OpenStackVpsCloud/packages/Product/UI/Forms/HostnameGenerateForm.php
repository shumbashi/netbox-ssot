<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\HostnameGenerateService;

class HostnameGenerateForm extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $this->builder->createField(
            FormInputText::class,
            "customconfigoption[" . HostnameGenerateService::HOSTNAME_FORMAT_FIELD . "]",
            true
        )->setPlaceholder(HostnameGenerateService::HOSTNAME_FORMAT_PLACEHOLDER);

        $this->builder->createField(
            Number::class,
            "customconfigoption[" . HostnameGenerateService::NEXT_INCREMENTAL_VALUE_FIELD . "]",
            true
        )->setDefaultValue(0);

        $this->builder->createField(
            FormInputText::class,
            "customconfigoption[" . HostnameGenerateService::RANDOM_PARAM_CHARS_FIELD . "]",
            true
        )->setPlaceholder(HostnameGenerateService::RANDOM_PARAM_DEFAULT_CHARS);

        $this->builder->createField(
            Number::class,
            "customconfigoption[" . HostnameGenerateService::RANDOM_PARAM_LENGTH_FIELD . "]",
            true
        )->setDefaultValue(0);

        $this->builder->createField(
            Switcher::class,
            "customconfigoption[" . HostnameGenerateService::OVERRIDE_DOMAIN_FIELD . "]",
            true
        );
    }
}