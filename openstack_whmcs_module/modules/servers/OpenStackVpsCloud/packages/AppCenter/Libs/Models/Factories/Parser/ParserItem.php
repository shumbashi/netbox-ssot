<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\Factories\Parser;

use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\AppConfigItem;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Parser\FieldParser;

class ParserItem
{
    protected ?AppConfigItem $configItem = null;
    protected ?FieldParser $parser = null;

    public function __construct(?AppConfigItem &$configItem = null, ?FieldParser &$parser = null)
    {
        $this->configItem = $configItem;
        $this->parser = &$parser;
    }

    public static function resetHistory()
    {
        ParserHistoryStack::clear();
    }

    public function __toString(): string
    {
        if (ParserHistoryStack::exists($this->configItem->getSetting()))
        {
            return ParserHistoryStack::get($this->configItem->getSetting()) ?? '';
        }

        ParserHistoryStack::allocate($this->configItem->getSetting());
        if (is_array($this->configItem->getValue()))
        {
            $values = $this->configItem->getValue();
            foreach ($values as &$valueItem)
            {
                $valueItem = $this->parser->setTemplate($valueItem)->parse();
            }
            $value = implode(',', $values);
        }
        else
        {
            $value = $this->parser->setTemplate($this->configItem->getValue())->parse();
        }

        /*Prevent redundant parsing*/
        $replacements = $this->parser->getReplacements();
        $replacements['config'][$this->configItem->getSetting()] = $value;
        $this->parser->addReplacement('config', $replacements['config']);
        ParserHistoryStack::push($this->configItem->getSetting(), $value);

        return $value;
    }
}