<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\App;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Modal\Modal;
use ModulesGarden\OpenStackVpsCloud\Components\TilesBar\TilesBar;
use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\App;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms\EditItemForm;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\CreateItemModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Modals\DeleteItemModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages\ItemsConfigDataTable;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Pages\ItemsDataTable;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\Modals\AppInstallModal;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Install\TileBars\AppTilesBar;

class BaseApp implements AppInterface
{
    use TranslatorTrait;

    public function load(): array
    {
        return [];
    }

    public function install(Container $formData, array $params, App $app)
    {
        throw new \Exception('Not implemented');
    }

    public function getDefaultConfig(): array
    {
        return [];
    }

    public function getUniqueConfigName(): string
    {
        return '';
    }

    public function getStaticConfigDuringUpdate(): array
    {
        return [];
    }

    public function getCreateModal(): Modal
    {
        return new CreateItemModal();
    }

    public function getEditForm(): Form
    {
        return new EditItemForm();
    }

    public function getDeleteModal(): Modal
    {
        return new DeleteItemModal();
    }

    public function getInstallModal(): Modal
    {
        return new AppInstallModal();
    }

    public function getItemsEditTable(): DataTable
    {
        return new ItemsConfigDataTable();
    }

    public function getItemsDataTable(): DataTable
    {
       return (new ItemsDataTable(static::class))
           ->setAjaxData(['type' => static::class]);
    }

    public function getTileBar(): TilesBar
    {
        return (new AppTilesBar(static::class));
    }
}