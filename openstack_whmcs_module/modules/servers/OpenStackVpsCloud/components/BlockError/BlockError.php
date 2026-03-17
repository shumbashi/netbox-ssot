<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\BlockError;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Div\Div;
use ModulesGarden\OpenStackVpsCloud\Components\PreBlock\PreBlock;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Exceptions\UserException;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;

class BlockError extends Div implements ClientAreaInterface, AdminAreaInterface
{
    protected \Throwable $exception;

    public function setException(\Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function loadHtml(): void
    {
        $alert = new AlertDanger();
        $alert->setText($this->exception->getMessage());
        $this->addElement($alert);

        if (Config::get('configuration.debug', false) && !($this->exception instanceof UserException))
        {
            $pre = new PreBlock();
            $pre->setContent($this->exception->getTraceAsString());

            $this->addElement($pre);
        }
    }
}
