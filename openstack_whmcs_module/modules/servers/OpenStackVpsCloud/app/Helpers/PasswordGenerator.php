<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers;

class PasswordGenerator
{
    protected int $length = 8;
    protected string $charset = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';

    public function generate()
    {
        return substr(str_shuffle($this->charset), 0, $this->length);
    }
}