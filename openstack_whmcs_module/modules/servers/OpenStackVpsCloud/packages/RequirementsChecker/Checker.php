<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Messages;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\CheckerInterface;

/**
 * Description of Handler
 */
class Checker
{
    protected array $alerts = [];
    protected array $warnings = [];

    public function check()
    {
        if (!defined('ADMINAREA'))
        {
            return;
        }

        $this->checkRequirements();
        $this->display();
    }

    protected function checkRequirements()
    {
        $checkers = Config::get('requirementsChecker');

        if (is_callable(current($checkers)))
        {
            $checkers = call_user_func(current($checkers));
        }

        $checkers = array_merge(require __DIR__ . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'DefaultCheckers.php', $checkers);

        foreach ($checkers as $requirement)
        {
            /**
             * @var $requirement CheckerInterface
             */
            if (!$requirement->check())
            {
                $requirement->getSeverity() == AbstractChecker::SEVERITY_WARNING ?
                    $this->warnings[] = $requirement->message() :
                    $this->alerts[] = $requirement->message();
            }
        }
    }

    protected function display()
    {
        foreach (array_merge($this->alerts, $this->warnings) as $result)
        {
            Messages::alert($result);
        }
    }

    /**
     * @return array
     */
    public function getAlerts(): array
    {
        return $this->alerts;
    }

    /**
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }
}
