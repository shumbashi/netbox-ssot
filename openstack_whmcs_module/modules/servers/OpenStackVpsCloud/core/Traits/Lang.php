<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Traits;

use ModulesGarden\OpenStackVpsCloud\Core\ServiceLocator;

/**
 * Trait Lang
 *
 * Provides language functionality for classes.
 * This trait is deprecated and should not be used in new code.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Traits
 * @deprecated Use language services directly instead
 */
trait Lang
{
    /**
     * Language service instance.
     *
     * @var null|\ModulesGarden\OpenStackVpsCloud\Core\Lang\Lang
     * @deprecated
     */
    protected $lang = null;

    /**
     * Load the language service instance.
     *
     * @return void
     * @deprecated Use dependency injection or service locator directly
     */
    public function loadLang()
    {
        if ($this->lang === null)
        {
            $this->lang = ServiceLocator::call('lang');
        }
    }
}
