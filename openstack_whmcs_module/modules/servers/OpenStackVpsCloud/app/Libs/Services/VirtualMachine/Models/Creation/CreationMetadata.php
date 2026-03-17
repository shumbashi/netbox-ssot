<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation;


class CreationMetadata extends Serializer
{
    /**
     * @var string
     */
    protected $adminPass;


    /**
     * @return string
     */
    public function getAdminPass(): string
    {
        return $this->adminPass;
    }

    /**
     * @param string $adminPass
     */
    public function setAdminPass(string $adminPass)
    {
        $this->adminPass = $adminPass;
    }


}