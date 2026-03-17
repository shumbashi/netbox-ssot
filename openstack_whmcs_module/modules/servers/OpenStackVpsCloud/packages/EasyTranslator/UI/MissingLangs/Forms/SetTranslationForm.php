<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertWarning;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Fields\BaseTranslation;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Fields\TranslationKey;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\MissingLangs\Providers\SetTranslationProvider;

class SetTranslationForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = SetTranslationProvider::class;
    protected string $providerAction = CrudProvider::ACTION_UPDATE;

    public function loadHtml(): void
    {
        if ($this->provider()->getValueById('langExists'))
        {
            $alert = new AlertWarning();
            $alert->setText($this->translate('langAlreadyExists'));
            $this->addElement($alert);
        }

        $this->builder->createField(HiddenField::class, 'id');
        $this->builder->createField(FormInputText::class, 'sourceText')
            ->setDisabled();

        if ($this->provider()->getValueById('hasSourceTranslation'))
        {
            $this->builder->createField(TranslationKey::class, 'sourceTranslation');
        }

        $this->builder->createField(TranslationKey::class, 'lang');
        $this->builder->createField(TextArea::class, 'translation');
    }
}