<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalDanger;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\AppModelFactory;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Model\Item;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Forms\AppInstallForm;

class AppInstallModal extends ModalDanger implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected ?string $form = AppInstallForm::class;

    public function loadHtml(): void
    {
        $item = Item::find(Request::get('formData')['id']);
        $service = Service::find(Params::get('serviceid'));

        $app = AppModelFactory::forServiceItem($service, $item);

        $this->setTitle($this->translate('title'));

        $alert = (new AlertDanger())
            ->setText($this->translate('alert', ['app_name' => $app->getName()]));

        $this->addElement($alert);

        if (!empty($app->getDescription())) {
            $description = new AlertInfo();
            $description->setText($app->getDescription());

            $this->addElement($description);
        }

        if (is_subclass_of($this->form, AbstractForm::class)) {
            $this->addElement(new $this->form);
        }
    }
}