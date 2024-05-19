<?php

namespace App\Utilities;

use Psr\Log\LoggerInterface;

/**
 * This is the wrapper class for monolog service
 * This class will internally use the LoggerInterface to log different messages
 *
 * @package AscoLms
 */
class MonologServiceProvider
{
    /**
     * Class constructor for Logger
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * This function will call different logger function to log various log messages
     *
     * @param  string $logType type of log message
     * @param  string $logMsg  detailed log message
     */
    public function writeLog(string $logType, string $logMsg)
    {
        switch ($logType) {
            case 'INFO':
                $this->logInfo($logMsg);
                break;
            case 'DEBUG':
                $this->logDebug($logMsg);
                break;
            case 'ERROR':
                $this->logError($logMsg);
                break;
            case 'CRITICAL':
                $this->logCritical($logMsg);
                break;
            default:
                $this->logInfo($logMsg);
                break;
        }
    }

    /**
     * Function to log the information messages
     *
     * @param  string $message
     */
    private function logInfo(string $message)
    {
        $this->logger->info($message);
    }

    /**
     * Function to log the debug messages
     *
     * @param  string $message
     */
    private function logDebug(string $message)
    {
        $this->logger->debug($message);
    }

    /**
     * Function to log the error messages
     *
     * @param  string $message
     */
    private function logError(string $message)
    {
        $this->logger->error($message);
    }

    /**
     * Function to log the critical error messages
     *
     * @param  string $message
     */
    private function logCritical(string $message)
    {
        $this->logger->critical($message);
    }
}
