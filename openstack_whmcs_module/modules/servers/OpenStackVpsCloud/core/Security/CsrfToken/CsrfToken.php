<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Security\CsrfToken;

use ModulesGarden\OpenStackVpsCloud\Core\Exceptions\SecurityTokenException;
use ModulesGarden\OpenStackVpsCloud\Core\Session\Session;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Config\Config;

/**
 * Class CsrfToken
 *
 * Handles CSRF token generation and validation for security protection.
 * Provides time-based token generation with configurable validity period.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\Security\CsrfToken
 */
class CsrfToken
{
    /**
     * Token validity time in seconds (5 minutes by default).
     *
     * @var int
     */
    protected static $validityTime = 900;

    /**
     * Generate a new CSRF token for the given name.
     *
     * @param string $name Token name/identifier
     * @return string Generated CSRF token
     */
    public static function generate(string $name): string
    {
        $startTime = (int)(time() / self::$validityTime);
        $endTime   = $startTime + 1;

        return self::token($name, $startTime, $endTime);
    }

    /**
     * Validate a CSRF token against the given name.
     *
     * @param string $name Token name/identifier
     * @param string $token Token to validate
     * @return void
     * @throws \Exception If token is invalid or expired
     */
    public static function validate(string $name, string $token): void
    {
        [$prefix, $startTime, $endTime] = explode(':', $token);
        $time = (int)(time() / self::$validityTime);

        if ($time < $startTime || $time > $endTime)
        {
            throw new SecurityTokenException('outdatedCsrfToken');
        }

        if (self::token($name, $startTime, $endTime) != $token)
        {
            throw new SecurityTokenException('invalidCsrfToken');
        }
    }

    /**
     * Generate a token hash using session data and configuration.
     *
     * @param string $name Token name/identifier
     * @param int $startTime Token start time
     * @param int $endTime Token end time
     * @return string Generated token hash
     */
    protected static function token(string $name, int $startTime, int $endTime): string
    {
        $session = new Session();
        $config  = new Config();
        global $cc_encryption_hash;

        $uid     = $session->get('uid');
        $adminId = $session->get('adminid');
        $domain  = $config->get('Domain');

        return sha1($name . $cc_encryption_hash . $uid . $adminId . $domain . $endTime . $startTime . $endTime) . ':' . $startTime . ':' . $endTime;
    }
}