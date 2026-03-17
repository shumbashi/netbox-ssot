<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\DynamicTranslations\Providers\DynamicTranslationsProvider;


class CreateLangForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = DynamicTranslationsProvider::class;
    protected string $providerAction = CrudProvider::ACTION_CREATE;

    public function loadHtml(): void
    {
        $this->builder->createField(FormInputText::class, 'regex', true)
            ->required();

        $languages = $this->provider()->getValueById('languages');

        foreach ($languages as $language)
        {
            $someField = (new TextArea())
                ->setName('translation_' . $language)
                ->setTitle(Translator::getBasedOnNamespace(self::class, "translation", ["language" => ucfirst($language)]));

            $this->builder->addField($someField);
        }
    }
}