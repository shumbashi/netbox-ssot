<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Database\Patches\Source;

interface PatchInterface
{
    public function execute():void;
    public function requires():array;
}