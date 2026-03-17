<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts;

interface ArrayProviderInterface extends RecordsListProviderInterface
{
    public function setData(array $data): self;
}
