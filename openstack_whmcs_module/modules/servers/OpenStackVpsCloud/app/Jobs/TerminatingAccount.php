<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Jobs\Abstracts\AbstractVmStageJobManager;

class TerminatingAccount extends AbstractVmStageJobManager
{
    const TERMINATING_ACCOUNT = 'terminatingAccount';
    const TERMINATE_VM = 'terminateVM';

    const FIRST_ACTION              = self::TERMINATE_VM;

    /**
     * @param string|null $stage
     * @param array $data
     * @return bool|mixed
     */
    public function runStageProcess(string $stage = null, array $data = [])
    {
        switch ($stage)
        {
            case self::TERMINATE_VM:
                $this->addTask(TerminationVM::class, $this->parseArguments(self::TERMINATING_ACCOUNT, $data));
                return true;
        }

        return false;

    }

    /**
     * Returns the first action of the terminating account process
     *
     * @return string
     */
    public function getFirstAction()
    {
        return self::FIRST_ACTION;
    }


    public function runClearProcess(string $job, array $data = [])
    {
        // TODO: Implement runClearProcess() method.
    }
}