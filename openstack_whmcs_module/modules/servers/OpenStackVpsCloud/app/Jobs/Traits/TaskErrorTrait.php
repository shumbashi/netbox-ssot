<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs\Traits;

trait TaskErrorTrait
{
    protected array $errors = [];

    protected function getErrorMessage()
    {
        return implode("\n", $this->errors);
    }
}