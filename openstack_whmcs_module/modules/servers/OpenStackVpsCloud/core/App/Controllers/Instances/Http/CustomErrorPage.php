<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\Http;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\UI\View;
use function ModulesGarden\OpenStackVpsCloud\Core\translate;

class CustomErrorPage extends View implements AdminAreaInterface, ClientAreaInterface
{
    public function __construct(string $message)
    {
        parent::__construct();

        $zeroBlock = new \ModulesGarden\OpenStackVpsCloud\Components\CustomErrorPage\CustomErrorPage();
        $zeroBlock->setTitle(translate("customErrorMessages." . $message . '.title'));
        $zeroBlock->setDescription(translate("customErrorMessages." . $message . '.description'));

        $this->addElement($zeroBlock);
    }

}