<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Pages;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms\ScheduledBackupsConfForm;
use ModulesGarden\OpenStackVpsCloud\Components\Div\Div;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class ScheduledBackupConf extends Div implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $section = new Widget();
        $section->setTitle($this->translate('ScheduledBackupsSection'));
        $section->addElement(new ScheduledBackupsConfForm());

        $this->addElement($section);
    }
}