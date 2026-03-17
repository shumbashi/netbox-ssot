<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts;

interface QueryProviderInterface extends RecordsListProviderInterface
{
    public function setQuery($query): self;
}
