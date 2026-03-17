<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms\MassEditItemConfigForm;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms\MassEditItemForm;

class MassEditItemConfigModal extends ModalEdit implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->addElement(new MassEditItemConfigForm());
        $this->setTitle($this->translate('title'));
    }
}