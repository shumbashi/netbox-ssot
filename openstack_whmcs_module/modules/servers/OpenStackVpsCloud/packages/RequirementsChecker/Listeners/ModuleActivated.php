<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Listeners;

use ModulesGarden\OpenStackVpsCloud\Core\Events\Listener;
use \ModulesGarden\OpenStackVpsCloud\Core\Services\Messages;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checker;
use function ModulesGarden\OpenStackVpsCloud\Core\make;

class ModuleActivated extends Listener
{
    public function handle($payload = [])
    {
        //Check requirementsChecker.php and set alerts in Messages
        $checker = new Checker();
        $checker->check();

        $alerts = $checker->getAlerts();

        if (!empty($alerts))
        {
            //get first Alert
            $alert = array_shift($alerts);
            throw new \Exception($alert);
        }
    }
}