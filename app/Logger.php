<?php

namespace App;

use App\Exceptions\FileException;
use Exception;

/**
 * Class Logger
 *
 * This class is used to log messages to a file.
 */
class Logger
{
    /**
     * Path to the log files directory.
     */
    private const LOG_PATH = __DIR__ . '/../logs/';

    /**
     * Types of log messages.
     */
    public const TYPES = [
        'info' => ['label' => 'INFO'],
        'debug' => ['label' => 'DEBUG'],
        'success' => ['label' => 'SUCCESS'],
        'warn' => ['label' => 'WARNING'],
        'error' => ['label' => 'ERROR'],
    ];

    /**
     * Creates the log folder if it does not exist.
     *
     * @return void
     */
    private static function createLogFolder($path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * Creates the log file if it does not exist.
     *
     * @return void
     */
    private static function createLogFile($file): void
    {
        if (!file_exists($file)) {
            $file = fopen($file, 'w');
            fclose($file);
        }
    }

    /**
     * Logs a message to the log file.
     *
     * @param string $message The message to log.
     *
     * @return void
     */
    private static function log(string $type = 'info', string $message = ''): void
    {
        $logPath = self::LOG_PATH . '/' . date('Y') . '/' . date('m');
        $logFile = $logPath . '/' . date('d') . '.log';

        if (!$message) return;

        if (!array_key_exists($type, self::TYPES)) $type = 'info'; // Default to 'info' if type is invalid.

        if (!file_exists($logPath)) self::createLogFolder($logPath);
        if (!file_exists($logFile)) self::createLogFile($logFile);

        if (!is_writable($logFile)) throw new FileException("The log file is not writable: $logFile", 403);

        try {
            $fileopen = (fopen($logFile, 'a'));

            $date = date('d-m-y h:i:s A');
            $ip = getenv("REMOTE_ADDR");
            $type = self::TYPES[$type]['label'];

            $line = PHP_EOL . "[" . $date . "][" . $ip . "] " . $type . ": " . $message;

            fwrite($fileopen, $line);
            fclose($fileopen);
        } catch (Exception $e) {
            throw new FileException("Error writing to the log file: " . $e->getMessage(), $e->getCode());
        }
    }

    public static function info(string $message = ''): void
    {
        self::log('info', $message);
    }

    public static function debug(string $message = ''): void
    {
        self::log('debug', $message);
    }

    public static function success(string $message = ''): void
    {
        self::log('success', $message);
    }

    public static function warn(string $message = ''): void
    {
        self::log('warn', $message);
    }

    public static function error(string $message = ''): void
    {
        self::log('error', $message);
    }
}
