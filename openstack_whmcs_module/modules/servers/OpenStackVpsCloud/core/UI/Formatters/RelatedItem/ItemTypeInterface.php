<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem;

interface ItemTypeInterface
{
    public function generateUrl():string;
    public function generateName():string;
}