<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class TaskAdditionalInfoTab extends Tab implements AdminAreaInterface
{
    protected array $additionalInfoElements = [];

    public function setAdditionalInfoElements(array $additionalInfoElements):self
    {
        $this->additionalInfoElements = $additionalInfoElements;

        return $this;
    }

    public function loadHtml(): void
    {
        $this->setTitle($this->translate('additionalInfo'));

        foreach ($this->additionalInfoElements as $additionalInfoElement)
        {
            $this->addElement($additionalInfoElement);
        }
    }
}