<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Traits;

/**
 * Trait IsAdmin
 *
 * Provides admin context detection functionality for classes.
 * This trait is deprecated and should not be used in new code.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Traits
 * @deprecated Use \ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin() directly instead
 */
trait IsAdmin
{
    /**
     * Cached admin status to avoid repeated checks.
     *
     * @var null|bool
     * @deprecated
     */
    protected $isAdminStatus = null;

    /**
     * Check if the current context is admin area.
     *
     * @return bool True if in admin context, false otherwise
     * @deprecated Use \ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin() directly
     */
    public function isAdmin()
    {
        if ($this->isAdminStatus === null)
        {
            $this->isAdminStatus = \ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin();
        }

        return $this->isAdminStatus;
    }
}
