<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Fields;

use ModulesGarden\OpenStackVpsCloud\Components\CopyToClipboardButton\CopyToClipboardButton;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputGroup\FormInputGroup;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;

class OriginalLang extends FormInputGroup
{
    public function loadHtml():void
    {
        $textField = (new FormInputText())
            ->setName($this->getSlot('name'))
            ->setDisabled();

        $copyButton = new CopyToClipboardButton();
        $copyButton->copyFromUsingName($textField->getName());;

        $this->addElement($textField);
        $this->addElement($copyButton);
    }
}