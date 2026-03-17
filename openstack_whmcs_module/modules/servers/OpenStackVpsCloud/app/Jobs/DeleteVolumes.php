<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class DeleteVolumes extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $volumes;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param array|null $volumes
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, array $volumes = null)
    {
        $this->params    = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->volumes   = $volumes;
        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool|string
     */
    public function runTaskAction()
    {
        try
        {
            foreach ($this->volumes as $volumeID)
            {
                Api::getInstance()->volume()->deleteVolume($volumeID);
            }

            return true;
        }
        catch (\Exception $exception)
        {
            return $exception->getMessage();
        }

    }
}