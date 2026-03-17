<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form\Builder;

use ModulesGarden\OpenStackVpsCloud\Components\Button\Button;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonCancel;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSubmitSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\GroupBuilders\DefaultBuilder;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroup;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;
use function ModulesGarden\OpenStackVpsCloud\Core\translator;

class Builder
{
    public const COMPONENT = 'Form';
    protected $buttonsContainer = null;
    protected $container = null;
    protected $defaultFormGroup = null;

    /**
     * @var AbstractForm|null
     */
    protected $form = null;

    public function __construct(AbstractForm $form)
    {
        $this->form             = $form;
        $this->container        = $form;
        $this->buttonsContainer = $form;
    }

    public function addButton(Button $button)
    {
        $this->buttonsContainer->addElement($button);

        return $this;
    }

    public function addElement($element): self
    {
        $this->container->addElement($element);

        return $this;
    }

    public function addButtonsContainer(ComponentContainerInterface $container): self
    {
        $this->form->addElement($container);
        $this->buttonsContainer = $container;

        return $this;
    }

    public function addDefaultContainer(ComponentContainerInterface $container): self
    {
        $this->form->addElement($container);
        $this->container = $container;

        return $this;
    }

    public function addFieldInContainer(ComponentContainerInterface $container, FormFieldInterface $field, bool $showTooltip = false, FormGroup $formGroup = null)
    {
        $container->addElement($this->createGroup($field, $showTooltip, $formGroup));

        return $field;
    }

    public function createGroup(FormFieldInterface $field, bool $showTooltip = true, FormGroup $formGroup = null)/*: FormField*/
    {
        return (new DefaultBuilder($this, $this->form, $this->defaultFormGroup))->build($field, $showTooltip, $formGroup);
    }

    public function createCancelButton(): self
    {
        $this->buttonsContainer->addElement((new ButtonCancel())
            ->setTitle(translator()->getBasedOnNamespaces([get_class($this->form)], 'cancel'))
            ->onClick(new FormSubmit($this->form)));

        return $this;
    }

    /**
     * @param string $classNamespace
     * @param $fieldName
     * @param bool $showTooltip
     * @param FormGroup|null $formGroup
     * @return FormFieldInterface
     */
    public function createField(string $classNamespace, string $fieldName, bool $showTooltip = false, FormGroup $formGroup = null)/*: FormField*/
    {
        return $this->addField((new $classNamespace())->setName($fieldName), $showTooltip, $formGroup);
    }

    /**
     * @param FormFieldInterface $field
     * @param bool $showTooltip
     * @param FormGroup|null $formGroup
     * @return FormFieldInterface
     */
    public function addField(FormFieldInterface $field, bool $showTooltip = false, FormGroup $formGroup = null)/*: FormField*/
    {
        $this->container->addElement($this->createGroup($field, $showTooltip, $formGroup));

        return $field;
    }

    public function createFieldInContainer(ComponentContainerInterface $container, string $classNamespace, string $fieldName, bool $showTooltip = false, FormGroup $formGroup = null)/*: FormField*/
    {
        $field = (new $classNamespace())->setName($fieldName);
        $container->addElement($this->createGroup($field, $showTooltip, $formGroup));

        return $field;
    }

    public function createSubmitButton(): self
    {
        $this->buttonsContainer->addElement((new ButtonSubmitSuccess())
            ->setTitle(translator()->getBasedOnNamespaces([get_class($this->form)], 'submit'))
            ->onClick(new FormSubmit($this->form)));

        return $this;
    }

    public function finish()
    {
    }

    public function setDefaultContainer(ComponentContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    public function setDefaultFormGroup(FormGroup $container): self
    {
        $this->defaultFormGroup = $container;

        return $this;
    }
}
