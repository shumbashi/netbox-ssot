<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Services;


class Request extends \ModulesGarden\OpenStackVpsCloud\Core\Http\Request
{
    public function getAll()
    {
        return $this->request->all();
    }
}
