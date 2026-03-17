<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS;

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use Psr\Log\LoggerInterface;

/**
 * Class LogActivity
 *
 * PSR-3 compliant logger implementation for WHMCS activity logging.
 * Wraps WHMCS's logActivity function to provide standardized logging interface.
 *
 * @package ModulesGarden\OpenStackVpsCloud\Core\WHMCS
 */
class LogActivity implements LoggerInterface
{
    /**
     * Log a message with the specified level.
     *
     * @param mixed $level Log level
     * @param string $message Log message
     * @param array $data Additional context data (not used in WHMCS logging)
     * @return void
     */
    public function log($level, $message, array $data = [])
    {
        \logActivity(sprintf("%s [%s] - %s", ModuleConstants::getModuleName(), $level, $message));
    }

    /**
     * Log an alert message.
     *
     * @param string $message Alert message
     * @param array $data Additional context data
     * @return void
     */
    public function alert($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log a critical message.
     *
     * @param string $message Critical message
     * @param array $data Additional context data
     * @return void
     */
    public function critical($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log a debug message.
     *
     * @param string $message Debug message
     * @param array $data Additional context data
     * @return void
     */
    public function debug($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log an emergency message.
     *
     * @param string $message Emergency message
     * @param array $data Additional context data
     * @return void
     */
    public function emergency($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log an error message.
     *
     * @param string $message Error message
     * @param array $data Additional context data
     * @return void
     */
    public function error($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log an info message.
     *
     * @param string $message Info message
     * @param array $data Additional context data
     * @return void
     */
    public function info($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log a notice message.
     *
     * @param string $message Notice message
     * @param array $data Additional context data
     * @return void
     */
    public function notice($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }

    /**
     * Log a warning message.
     *
     * @param string $message Warning message
     * @param array $data Additional context data
     * @return void
     */
    public function warning($message, array $data = [])
    {
        $this->log(__FUNCTION__, $message, $data);
    }
}