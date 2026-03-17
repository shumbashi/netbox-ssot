<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons;

use ModulesGarden\OpenStackVpsCloud\Components\TileButton\TileButton;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

class VpsActionButton extends TileButton
{
    public function setImagePath(string $imagePath): self
    {
        try {
            $path = ModuleConstants::getFullPath('resources', 'assets', 'img', 'actions', $imagePath);
            parent::setImagePath($path);
        }
        catch (\Exception $e) {
            // icon not found
        }
        return $this;
    }
}
