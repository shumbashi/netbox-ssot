<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Traits;

/**
 * Trait Template
 *
 * Provides template directory and name management functionality for classes.
 * This trait is deprecated and should not be used in new code.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Traits
 * @deprecated Use template services directly instead
 */
trait Template
{
    /**
     * Template directory path.
     *
     * @var string|null
     * @deprecated
     */
    protected $templateDir = null;

    /**
     * Template name.
     *
     * @var string|null
     * @deprecated
     */
    protected $templateName = null;

    /**
     * Get the template directory path.
     *
     * @return string|null The template directory path
     * @deprecated Use template services directly
     */
    public function getTemplateDir()
    {
        return $this->templateDir;
    }

    /**
     * Set the template directory path.
     *
     * @param string $templateDir Template directory path
     * @return $this
     * @deprecated Use template services directly
     */
    public function setTemplateDir($templateDir)
    {
        $this->templateDir = $templateDir;

        return $this;
    }

    /**
     * Get the template name.
     *
     * @return string|null The template name
     * @deprecated Use template services directly
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Set the template name.
     *
     * @param string $templateName Template name
     * @return $this
     * @deprecated Use template services directly
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;

        return $this;
    }
}
