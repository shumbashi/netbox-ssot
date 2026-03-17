<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Traits;

use function ob_clean;
use function ob_get_contents;
use function ob_start;

/**
 * Trait OutputBuffer
 *
 * Provides output buffer management functionality for classes.
 * This trait is deprecated and should not be used in new code.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Traits
 * @deprecated Use output buffering functions directly instead
 */
trait OutputBuffer
{
    /**
     * Clean the output buffer and restart buffering.
     *
     * @return $this
     * @deprecated Use ob_clean() and ob_start() directly
     */
    protected function cleanOutputBuffer()
    {
        $outputBuffering = ob_get_contents();
        if ($outputBuffering !== false)
        {
            if (!empty($outputBuffering))
            {
                ob_clean();
                ob_start();
            }
            else
            {
                ob_start();
            }
        }

        return $this;
    }
}
