<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\HtmlEditor;

use ModulesGarden\OpenStackVpsCloud\Core\Components\FormFields\FormField;

class HtmlEditor extends FormField
{
    public const COMPONENT = 'HtmlEditor';

    public function enableAutoSave(bool $autoSave = true):self
    {
        $this->setSlot('autoSaveEnabled', $autoSave);

        return $this;
    }

    public function enableEmoticons(bool $emoticons = true):self
    {
        $this->setSlot('emoticonsEnabled', $emoticons);

        return $this;
    }
}