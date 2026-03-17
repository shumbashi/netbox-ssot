<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Providers\SnapshotsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Widget\Forms\FormConstants;

class MassDeleteSnapshotForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = SnapshotsProvider::class;
    protected string $providerAction = CrudProvider::ACTION_DELETE;

    public function loadHtml(): void
    {
        $snapshotID = (new HiddenField())
            ->setName('snapshotID');

        $this->builder->addField($snapshotID);
    }
}