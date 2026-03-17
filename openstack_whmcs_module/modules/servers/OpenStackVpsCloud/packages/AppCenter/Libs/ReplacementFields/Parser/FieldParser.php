<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Parser;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Smarty;

class FieldParser
{
    protected array $replacements = [];

    protected string $template = '';

    public function addReplacement($name, $instance)
    {
        $this->replacements[$name] = $this->parseHtmlEntitiesElements($instance);
        return $this;
    }

    public function parse()
    {
        return Smarty::fetch($this->template, $this->replacements);
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = html_entity_decode($template);
        return $this;
    }

    public function setReplacements(array $replacements): FieldParser
    {
        foreach ($replacements as $name => $value)
        {
            $this->addReplacement($name, $value);
        }

        return $this;
    }

    public function getReplacements(): array
    {
        return $this->replacements;
    }

    protected function parseHtmlEntitiesElements($array)
    {
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $array[$key] = $this->parseHtmlEntitiesElements($value);
            }
            elseif (is_string($value))
            {
                $array[$key] = html_entity_decode($value);
            }
        }

        return $array;
    }
}