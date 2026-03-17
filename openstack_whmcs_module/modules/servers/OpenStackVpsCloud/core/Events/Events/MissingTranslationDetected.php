<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Events\Events;

use ModulesGarden\OpenStackVpsCloud\Core\Events\Event;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use \Symfony\Component\Translation\MessageCatalogueInterface;

class MissingTranslationDetected extends Event
{
    protected string $lang;
    protected string $sourceText;
    protected MessageCatalogueInterface $catalogue;
    protected array $replacements;

    public function __construct(string $lang, MessageCatalogueInterface $catalogue, array $replacements, string $sourceText = null)
    {
        $this->lang         = $lang;
        $this->catalogue    = $catalogue;
        $this->replacements = $replacements;
        $this->sourceText   = $sourceText ?: Arr::last((array)explode('.', $lang));
    }

    public function getLocale(): string
    {
        return $this->catalogue->getLocale();
    }

    public function getCatalog(): MessageCatalogueInterface
    {
        return $this->catalogue;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getReplacements(): array
    {
        return $this->replacements;
    }

    public function getSourceText(): string
    {
        return $this->sourceText;
    }
}