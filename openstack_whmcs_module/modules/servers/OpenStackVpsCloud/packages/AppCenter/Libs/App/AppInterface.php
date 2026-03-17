<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\App;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\Modal;
use ModulesGarden\OpenStackVpsCloud\Components\TilesBar\TilesBar;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;

interface AppInterface
{
    public function load(): array;

    public function install(Container $formData, array $params, App $app);

    public function getDefaultConfig(): array;

    public function getUniqueConfigName(): string;

    public function getStaticConfigDuringUpdate(): array;

    public function getCreateModal(): Modal;

    public function getEditForm(): Form;

    public function getDeleteModal(): Modal;

    public function getInstallModal(): Modal;

    public function getItemsDataTable(): DataTable;

    public function getItemsEditTable(): DataTable;

    public function getTileBar(): TilesBar;
}