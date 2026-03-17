<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\URL;

class Client
{
    public static function productDetails(int $hostingId, array $parameters = [])
    {
        $parameters['action'] = 'productdetails';
        $parameters['id'] = $hostingId;

        return \ModulesGarden\OpenStackVpsCloud\Core\Routing\Url::clientarea('clientarea.php', $parameters);
    }

    public static function redirectToLoginPage(): void
    {
        \App::redirectToRoutePath("login-index");
    }
}