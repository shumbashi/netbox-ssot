<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ServerConfig;
use ModulesGarden\OpenStackVpsCloud\App\Repositories\ServerRepository;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Redirect;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Request;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL\Admin;

class RefreshResourceProvider extends CrudProvider
{
    const ACTION_REFRESH = 'refresh';

    public function refresh()
    {
        $server = ServerRepository::findByGroupId(Request::get('servergroup'));

        (new ServerConfig())->refreshResources(Request::get('id'), $server->id);

        return (new Response())
            ->setActions([
                new Redirect(html_entity_decode(Admin::productConfig(Request::get('id'), ['tab' => 3])))
            ]);

    }
}