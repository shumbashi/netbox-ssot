<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Toolbar;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Translator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButtonEdit;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\ModalEdit;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Action;

class Toolbar extends \ModulesGarden\OpenStackVpsCloud\Components\Toolbar\Toolbar
{
    protected AbstractForm $form;

    public function loadHtml(): void
    {
        if (!isset($this->form))
        {
            return;
        }

        $this->form->onSubmit(Action::reloadParent());

        $modal = new ModalEdit();
        $modal->setTitle(Translator::getBasedOnNamespaces([get_class($this->form), ModalEdit::class], 'title'));
        $modal->addElement($this->form);

        $icon = new IconButtonEdit();
        $icon->setTitle(Translator::getBasedOnNamespaces([get_class($this->form) . "\EditButton", IconButtonEdit::class], 'title'));
        $icon->onClick(Action::modalOpen($modal));

        $this->addElement($icon);
    }

    public function setForm(AbstractForm $form):self
    {
        $this->form = $form;

        return $this;
    }
}
