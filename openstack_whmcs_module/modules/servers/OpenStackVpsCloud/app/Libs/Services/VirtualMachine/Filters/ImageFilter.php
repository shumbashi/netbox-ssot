<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters;
class ImageFilter extends Filter
{
    public function filterBySource($source): self
    {
        $this->data = array_filter($this->data, function($image) use ($source) {
            return $image['source'] === $source;
        });

        return $this;
    }
}