<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Widget;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Components\SwitchableContainer\SwitchableContainer;
use ModulesGarden\OpenStackVpsCloud\Components\IconButton\IconButton;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Collapse;

/**
 * Widget contains specified buttons in the toolbar that collapse its elements.
 */
class CollapsibleWidget extends Widget
{
    protected bool $defaultHide = false;

    public function __construct()
    {
        parent::__construct();

        $this->setTranslations([
            'show_button_title',
            'hide_button_title'
        ]);
    }

    public function postLoadHtml(): void
    {
        $this->addClass("lu-widget-collapsible");
        $buttonsContainer = new SwitchableContainer();

        $buttonShow = $this->getShowButton();
        $buttonHide = $this->getHideButton();

        $mainWidgetContainer = $this->packWidgetElementsToOneContainer();

        $buttonHide->onClick(new Collapse($mainWidgetContainer, $this->defaultHide));
        $buttonShow->onClick(new Collapse($mainWidgetContainer, $this->defaultHide));

        $buttonsContainer->addElement($buttonHide);
        $buttonsContainer->addElement($buttonShow);

        $this->sortButtons($buttonsContainer);
        $this->addElement($mainWidgetContainer);
        $this->addToToolbar($buttonsContainer);
    }

    /**
     * @return $this
     */
    public function setDefaultClosed(): self
    {
        $this->defaultHide = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultOpen(): self
    {
        $this->defaultHide = false;

        return $this;
    }

    protected function getWidgetCollapsibleElements(): array
    {
        return array_filter(
            $this->getSlot('elements.elements') ?: [],
            fn($element) => is_subclass_of($element, AbstractComponent::class)
        );
    }

    protected function sortButtons($container):void
    {
        if (!$this->defaultHide)
        {
            return;
        }

        $container->setSlot('elements.elements', array_reverse($container->getSlot('elements.elements')));
    }

    protected function getShowButton():IconButton
    {
        return (new IconButton())
            ->setIcon('chevron-down')
            ->setTitle($this->translate('show_button_title'));
    }

    protected function getHideButton():IconButton
    {
        return (new IconButton())
            ->setIcon('chevron-up')
            ->setTitle($this->translate('hide_button_title'));
    }

    protected function packWidgetElementsToOneContainer():Container
    {
        $container = new Container();

        foreach ($this->getWidgetCollapsibleElements() as $element)
        {
            $container->addElement($element);
        }

        $this->clearElements();

        return $container;
    }
}