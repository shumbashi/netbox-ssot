<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\TableSimple;

class TableSimpleAlignedColumns extends TableSimple
{
    public function __construct()
    {
        parent::__construct();
        $this->addClass("lu-table-layout-fixed");
    }
}