<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\View;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\Alert;
use ModulesGarden\OpenStackVpsCloud\Core\Services\Messages;
use function ModulesGarden\OpenStackVpsCloud\Core\make;

class AlertsBuilder
{
    public function create(): array
    {
        $alerts = [];
        foreach (make(Messages::class)->getAlerts() as $message)
        {
            $alerts[] = (new Alert())
                ->setText($message->getText())
                ->setType($message->getType())
                ->setOutline();
        }

        return $alerts;
    }
}