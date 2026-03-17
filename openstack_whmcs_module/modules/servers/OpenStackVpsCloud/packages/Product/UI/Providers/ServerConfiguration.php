<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\SetFieldValueById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class ServerConfiguration extends CrudProvider
{
    use TranslatorTrait;

    public function read()
    {
        $this->data = $this->formData;
    }

    public function create()
    {
        (new \ModulesGarden\OpenStackVpsCloud\Packages\Product\Services\ServerConfiguration(Request::get('id', 0)))
            ->save($this->formData['serverconfig']);

        return (new Response())
            ->setSuccess($this->translate("create_success"))
            ->setActions([
                new ModalClose(),
                new SetFieldValueById('serverHash', json_encode($this->formData['serverconfig'], JSON_PRETTY_PRINT)),
            ]);
    }
}
