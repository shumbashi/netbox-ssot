<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Modals;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonClose;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSubmitSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\Modal;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalFormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Forms\RefreshLangsForm;
use ModulesGarden\OpenStackVpsCloud\Packages\EasyTranslator\UI\StaticTranslations\Providers\RefreshLangsProvider;

class RefreshLangsModal extends Modal implements AjaxComponentInterface, AdminAreaInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('title'));

        $provider = new RefreshLangsProvider();
        $provider->read();
        $missingLangsCount = $provider->getValueById('missingLangsCount');

        if ($missingLangsCount > 0)
        {
            $message = $this->translate("missingLangsFoundInfo", ["missingLangsCount" => $missingLangsCount]);

            $this->addSubmitButton();
            $this->addCloseButton();

            $this->addElement((new AlertInfo())->setText($message));
            $this->addElement(new RefreshLangsForm());

        }
        else
        {
            $message = $this->translate("noMissingLangsFound");

            $this->addCloseButton();
            $this->addElement((new AlertInfo())->setText($message));
        }
    }

    protected function addSubmitButton()
    {
        $this->addActionButton(
            (new ButtonSubmitSuccess())
                ->setTitle($this->translate('add'))
                ->onClick(new ModalFormSubmit($this))
        );
    }

    protected function addCloseButton()
    {
        $this->addActionButton(
            (new ButtonClose())
                ->setTitle($this->translate('close'))
                ->onClick(new ModalClose($this))
        );
    }
}