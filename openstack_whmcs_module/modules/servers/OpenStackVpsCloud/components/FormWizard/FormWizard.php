<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormWizard;

use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormWizardStep\FormWizardStep;
use ModulesGarden\OpenStackVpsCloud\Components\Tab\Tab;
use ModulesGarden\OpenStackVpsCloud\Components\Widget\Widget;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ActionInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;

class FormWizard extends Form
{
    public const COMPONENT = 'FormWizard';

    protected bool $removePreviousStepButton = false;
    protected string $providerValidateAction = "stepValidation";

    public function addStep(FormWizardStep $step)
    {
        $elementsLength = count($this->getSlot('elements.elements') ?: []);

        $tab = new Tab();

        if (Request::get('providerAction') == $this->providerValidateAction && $elementsLength != Request::get('step', 0))
        {
            $this->addComponent('elements', $tab);
            return;
        }

        $tab->setTitle($step->getTitle());
        $tab->addElement((new Widget())->addElement($step));
        $this->addComponent('elements', $tab);
    }

    public function removePreviousStepButton()
    {
        $this->removePreviousStepButton = true;
    }

    public function preLoadHtml(): void
    {
        $this->providerActionsToValidate[] = strtolower($this->providerValidateAction);
        $this->buildProviderConfig();
        $this->buildButtonsContainer();

        parent::preLoadHtml();
    }

    public function onNextStep(ActionInterface $action): self
    {
        $this->addAction('onNextStep', $action);

        return $this;
    }

    protected function buildProviderConfig()
    {
        $this->setSlot('providerValidateAction', $this->providerValidateAction);
    }

    protected function buildButtonsContainer()
    {
        if (!$this->removePreviousStepButton)
        {
            $this->buildPreviousStepButton();
        }

        $this->buildNextStepButton();
        $this->buildSubmitButton();
    }

    protected function buildPreviousStepButton()
    {
        $previousStepButton = new ButtonInfo();
        $previousStepButton->setTitle("Previous");

        $this->setSlot('elements.buttons.previousStep', $previousStepButton);
    }

    protected function buildNextStepButton()
    {
        $nextStepButton = new ButtonInfo();
        $nextStepButton->setTitle("Next");

        $this->setSlot('elements.buttons.nextStep', $nextStepButton);
    }

    protected function buildSubmitButton()
    {
        $submitButton = new ButtonSuccess();
        $submitButton->setTitle("Submit");
        $submitButton->onClick(new FormSubmit($this));

        $this->setSlot('elements.buttons.submit', $submitButton);
    }
}