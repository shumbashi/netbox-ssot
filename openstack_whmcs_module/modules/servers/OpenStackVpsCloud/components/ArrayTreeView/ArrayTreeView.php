<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeView;

use ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeView\Traits\ElementsExpanderTrait;
use ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeView\Traits\ElementsPrefixTrait;
use ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeView\Traits\ExpanderOnBeginningTrait;
use ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeView\Traits\KeyValueSeparatorTrait;
use ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeView\Traits\HiddenKeysModeTrait;
use ModulesGarden\OpenStackVpsCloud\Components\ArrayTreeViewItem\ArrayTreeViewItem;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;

class ArrayTreeView extends AbstractComponent
{
    use ElementsPrefixTrait;
    use KeyValueSeparatorTrait;
    use ElementsExpanderTrait;
    use ExpanderOnBeginningTrait;
    use HiddenKeysModeTrait;

    public const COMPONENT = 'ArrayTreeView';
    public const DEFAULT_ELEMENTS_EXPANDER = '(...)';

    protected array $elements = [];

    public function __construct(array $elements = [])
    {
        parent::__construct();

        $this->elements = $elements;
        $this->setElementsExpander(self::DEFAULT_ELEMENTS_EXPANDER);
    }

    final public function elementsSlotBuilder():array
    {
        return $this->buildElements();
    }

    protected function buildElements():array
    {
        $elements = [];

        foreach ($this->elements as $key => $value)
        {
            $elements[] = (new ArrayTreeViewItem($key, $value))
                ->setElementsPrefix($this->elementsPrefix)
                ->setKeyValueSeparator($this->keyValueSeparator)
                ->setElementsExpander($this->elementsExpander)
                ->enableExpanderOnBeginning($this->expanderOnBeginning)
                ->enableHiddenKeysMode($this->hiddenKeysMode);
        }

        return $elements;
    }
}