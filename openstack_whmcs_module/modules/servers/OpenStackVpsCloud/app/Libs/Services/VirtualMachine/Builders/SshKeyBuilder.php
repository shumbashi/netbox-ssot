<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Decorators\KeyPairDecorator;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\KeyPairModel;

class SshKeyBuilder extends BaseBuilder
{
    public function build(string $sshPublicKey = null)
    {
        /**
         * @var KeyPairModel $sshKey
         */
        $sshKey = $this->tenant->keyPair();
        $sshKey->setName(KeyPairDecorator::nameDecorator($this->params['serviceid']));

        if ($sshPublicKey && isset($sshPublicKey))
        {
            $sshKey->setPublic($sshPublicKey);
        }

        $sshKey->create();

        return $sshKey;
    }
}