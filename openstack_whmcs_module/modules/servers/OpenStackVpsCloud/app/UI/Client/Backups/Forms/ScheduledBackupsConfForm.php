<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Forms;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers\ScheduledBackupsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Button\ButtonSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class ScheduledBackupsConfForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    const ID = 'scheduledBackupConf';

    protected string $providerAction = CrudProvider::ACTION_UPDATE;
    protected string $provider = ScheduledBackupsProvider::class;

    public function loadHtml(): void
    {
        $this->setId(self::ID);

        $productConfig = new ProductConfiguration(Params::get('serviceid'));

        $this->builder->addField((new Switcher())
            ->setName('enableBackup')
            ->setDescription($this->translate('enableBackupDescription')),true);

        $this->builder->addField((new Number())
            ->setName('timeInterval')
            ->setDefaultValue((int)$productConfig->getMinimalTimeBetweenBackups())
            ->setMin((int)$productConfig->getMinimalTimeBetweenBackups())
            ->numeric()
            ->setDescription($this->translate('timeIntervalDescription', ['configTimeInterval' => $productConfig->getMinimalTimeBetweenBackups()])), true);

        $this->addToToolbar((new ButtonSuccess())
            ->setName('submit')
            ->setTitle($this->translate('submit.title'))
            ->onClick((new FormSubmit($this))));
    }
}