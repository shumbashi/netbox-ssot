<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Security\IntegrityToken;

use ModulesGarden\OpenStackVpsCloud\Core\Exceptions\SecurityTokenException;
use ModulesGarden\OpenStackVpsCloud\Core\Session\Session;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Config\Config;

class IntegrityToken
{
    public static function generate(array $fieldsValues): string
    {
        return self::token($fieldsValues);
    }

    public static function validate(array $fieldsValues, string $token): void
    {
        if (self::token($fieldsValues) !== $token)
        {
            throw new SecurityTokenException('invalidIntegrityToken');
        }
    }


    protected static function token(array $fieldsValues): string
    {
        $session = new Session();
        $config  = new Config();
        global $cc_encryption_hash;

        $uid     = $session->get('uid');
        $adminId = $session->get('adminid');
        $domain  = $config->get('Domain');

        return sha1(implode('', $fieldsValues) . implode('', array_keys($fieldsValues)) . $cc_encryption_hash . $uid . $adminId . $domain);
    }
}