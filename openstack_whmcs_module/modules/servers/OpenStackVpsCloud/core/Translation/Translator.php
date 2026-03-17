<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Translation;

use ModulesGarden\OpenStackVpsCloud\Core\Events\Events\MissingTranslationDetected;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\Loader\File;
use \Symfony\Component\Translation\MessageCatalogueInterface;
use function ModulesGarden\OpenStackVpsCloud\Core\fire;

class Translator extends \Symfony\Component\Translation\Translator
{
    protected const REPLACEMENTS_PREFIX = ":";
    protected const DEFAULT_FALLBACK_LOCALES = ['english'];
    protected string $defaultDomain = 'messages';
    private ?string $lastUsedLocale = null;

    public function __construct()
    {
        parent::__construct((new Selector())->getLanguage());

        $this->setDefaultFallbackLocales();
        $this->addLoader('file', new File());

        foreach (Locales::getAvailable() as $locale)
        {
            $this->addResource("file", '', $locale);
        }
    }

    public function getBasedOnNamespaces(array $namespaces, string $key, array $replace = [], $locale = null, $fallback = true)
    {
        foreach ($namespaces as $namespace)
        {
            $langKey     = $this->convertNamespaceToLangKey($namespace, $key);
            $translation = $this->get($langKey, $replace, $locale, $fallback);

            if ($translation !== $langKey)
            {
                if (!$this->lastUseLocaleIsOriginal())
                {
                    $this->fireMissingTranslationEvent($key, $this->convertNamespaceToLangKey($namespaces[0], $key), $replace);
                }

                return $translation;
            }
        }

        $baseNamespaceLangKey = $this->convertNamespaceToLangKey($namespaces[0], $key);

        $results = $this->fireMissingTranslationEvent($key, $baseNamespaceLangKey, $replace);

        $customTranslation = current(array_filter($results, function ($value) {
            return is_string($value) && !empty($value);
        }));

        return $customTranslation ?: (Config::get('configuration.debug', false) ? $baseNamespaceLangKey : $key);
    }
    /**
     * Get translation based on provided key. You must specify full path
     * @param $key
     * @param array $replace
     * @param $locale
     * @param $fallback
     * @return mixed
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        return self::trans($key, $this->addReplacementsPrefix($replace), $this->defaultDomain, empty(trim((string)$locale)) ? null : $locale);
    }

    /**
     * @param string $namespace
     * @param string $key
     * @param array $replace
     * @param $locale
     * @param $fallback
     * @return mixed
     */
    public function getBasedOnNamespace(string $namespace, string $key, array $replace = [], $locale = null, $fallback = true)
    {
        return $this->get($this->convertNamespaceToLangKey($namespace, $key), $replace, $locale, $fallback);
    }

    public function setDefaultDomain(string $domain): self
    {
        $this->defaultDomain = $domain;

        return $this;
    }

    /**
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    public function trans(?string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $localeToUse = $locale ?? $this->getLocale();
        $catalogue   = $this->getCatalogue($localeToUse);
        $domainToUse = $domain ?? $this->defaultDomain;

        if (array_key_exists($id, $catalogue->all($domainToUse)))
        {
            $this->lastUsedLocale = $localeToUse;
            return parent::trans($id, $parameters, $domain, $locale);
        }

        foreach ($this->getFallbackCatalogueChain($catalogue) as $fallbackCatalogue)
        {
            if (array_key_exists($id, $fallbackCatalogue->all($domainToUse)))
            {
                $this->lastUsedLocale = $fallbackCatalogue->getLocale();
                return parent::trans($id, $parameters, $domain, $fallbackCatalogue->getLocale());
            }
        }

        $this->lastUsedLocale = null;
        return parent::trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param ?string $default The default locale to return if no locale was found
     *
     * @return string The last used locale that found a translation
     */
    public function getLastUsedLocale(?string $default = null): ?string
    {
        return $this->lastUsedLocale ?: $default;
    }

    protected function setDefaultFallbackLocales():void
    {
        $this->setFallbackLocales(self::DEFAULT_FALLBACK_LOCALES);
    }

    protected function fireMissingTranslationEvent(string $key, string $namespace, array $replace = []):array
    {
        return fire(new MissingTranslationDetected($namespace, $this->getCatalogue($this->getLocale()), $replace, $key));
    }

    protected function lastUseLocaleIsOriginal():bool
    {
        return $this->getLocale() == $this->getLastUsedLocale($this->getLocale());
    }

    protected function convertNamespaceToLangKey(string $namespace, string $key):string
    {
        return ($namespace ? NamespaceConverter::convert($namespace) . '.' : '') . $key;
    }

    protected function getFallbackCatalogueChain(MessageCatalogueInterface $catalogue): array
    {
        $chain = [];

        while ($fallback = $catalogue->getFallbackCatalogue())
        {
            $chain[]   = $fallback;
            $catalogue = $fallback;
        }

        return $chain;
    }

    private function addReplacementsPrefix(array $replacements): array
    {
        return array_combine(
            array_map(function ($value) {
                return self::REPLACEMENTS_PREFIX . trim($value, self::REPLACEMENTS_PREFIX);
            }, array_keys($replacements)),
            array_values($replacements)
        );
    }
}
