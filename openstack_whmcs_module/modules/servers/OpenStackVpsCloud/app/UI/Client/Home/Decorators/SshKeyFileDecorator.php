<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Decorators;


use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\KeyPairModel;

/**
 * Class SshKeyFileDecorator
 */
class SshKeyFileDecorator
{
    const RSA = 'rsa';
    const PEM = 'pem';

    /**
     * @param int $serviceId
     * @return string
     */
    public static function decoratePublicKeyName(int $serviceId)
    {
        return $serviceId . '_' . KeyPairModel::PUBLIC . '.' . self::PEM;
    }

    /**
     * @param int $serviceId
     * @return string
     */
    public static function decoratePrivateKeyName(int $serviceId)
    {
        return $serviceId . '_' . KeyPairModel::PRIVATE . '.' . self::RSA;
    }
}