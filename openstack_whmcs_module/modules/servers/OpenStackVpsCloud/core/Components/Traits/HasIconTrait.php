<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Traits;

trait HasIconTrait
{
    /**
     * @param string $icon
     * @return self
     **/
    public function setIcon(string $icon): self
    {
        $this->setSlot('icon', $icon);

        return $this;
    }
}