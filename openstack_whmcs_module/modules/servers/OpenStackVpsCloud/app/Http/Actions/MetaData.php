<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Http\Actions;

use ModulesGarden\OpenStackVpsCloud\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;

class MetaData extends AddonController
{
    public function execute($params = null)
    {
        return [
            'DisplayName'       => Config::get('configuration.systemName'),
            'APIVersion'        => '1.1',
            'RequiresServer'    => true,
//            'DefaultNonSSLPort' => '8006',
//'            'DefaultSSLPort'    => '8006','?
            //            'ServiceSingleSignOnLabel'                => 'Login to myModule Client',
            //            'AdminSingleSignOnLabel'                  => 'Login to myModule Admin',
            //            'ListAccountsUniqueIdentifierDisplayName' => 'Domain',
            //            'ListAccountsUniqueIdentifierField'       => 'domain',
            //            'ListAccountsProductField'                => 'configoption1',
        ];
    }
}
