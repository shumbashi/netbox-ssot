<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Sections\Forms;

use ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Components\Switcher\Switcher;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ServerConfiguration;

class VmSettings extends Form implements AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->builder = BuilderCreator::twoColumns($this);
    }

    public function loadHtml(): void
    {
        $this->setContainerTag('div');

        $server = ServerRepository::findByGroupId(Request::get('servergroup'));
        $serverConfiguration = (new ServerConfiguration($server->id))->get();

        $volumeInterface = json_decode(base64_decode($serverConfiguration['volumeEndpoint']))->interface;
        if (!empty($volumeInterface)) {
            $this->builder->addField((new Dropdown())->setName('customconfigoption[volume_type]'));
            $this->builder->addField((new Switcher())->setName('customconfigoption[use_volumes]')->setDescription($this->translate('customconfigoption[use_volumes].description')), true);
            $this->builder->addField((new Number())->setName('customconfigoption[volume_size]')->setDescription($this->translate('customconfigoption[volume_size].description')), true);
        }

        $this->builder->addField((new Dropdown())->setName('customconfigoption[security_groups]')->setMultiple());
        $this->builder->addField((new Dropdown())->setName('customconfigoption[console_type]'));
        $this->builder->addField((new Dropdown())->setName('customconfigoption[rescue_image_ref]'));

        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_keypair]')->setDescription($this->translate("customconfigoption[caf_keypair].description")), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[protect_vm_create]')->setDescription($this->translate('customconfigoption[protect_vm_create].description')), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[delete_keypair]')->setDescription($this->translate('customconfigoption[delete_keypair].description')), true);
        $this->builder->addField((new Switcher())->setName('customconfigoption[caf_change_password]')->setDescription($this->translate('customconfigoption[caf_change_password].description')), true);
    }
}

