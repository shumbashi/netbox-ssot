<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation;


class CreationSecurityGroups extends Serializer
{
    /**
     * @var array
     */
    protected $securityGroups = [];


    public function addSecurityGroup($group)
    {
        $this->securityGroups[] = [
            'name' => $group->name
        ];
    }

    /**
     * @return array
     */
    public function getSecurityGroups()
    {
        return $this->securityGroups;
    }

    /**
     * @param array $securityGroups
     */
    public function setSecurityGroups(array $securityGroups)
    {
        $this->securityGroups = $securityGroups;
    }


}