<?php

namespace App\Http;

use Exception;
use App\Exceptions\NotFoundException;
use App\Exceptions\FileException;

trait Utils
{
    const VIEWS_PATH = __DIR__ . '/../../ressources/views/';

    /**
     * Redirect to a different page
     * 
     * @return void
     */
    public static function redirect(string $uri = '/'): void
    {
        if (headers_sent()) return;

        header('Location: ' . APP_URL . $uri);
        exit;
    }

    /**
     * Send a JSON response
     * 
     * @param int $status
     * @param mixed $data
     * @param array $headers
     * @return void
     */
    public function json(int $status, mixed $data = null, array $headers = ['methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], 'origin' => '*', 'cache' => 0]): void
    {
        if (headers_sent()) return;

        http_response_code($status);
        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
        header("Content-type: application/json; charset=utf-8");

        if (is_array($headers) && !empty($headers)) {
            if (isset($headers['method'])) {
                header('Access-Control-Allow-Methods: ' . implode(', ', $headers['methods']));
            }
            if (isset($headers['origin'])) {
                header('Access-Control-Allow-Origin: ' . $headers['origin']);
            }
            if (isset($headers['cache']) && $headers['cache'] > 0) {
                $seconds = $headers['cache'];
                $ts = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";
                header("Expires: $ts");
                header("Pragma: cache");
                header("Cache-Control: max-age=$seconds");
            }
        }

        echo json_encode($data);
        exit;
    }

    /**
     * Render a view
     * 
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function view(string $name, mixed $data = null): void
    {
        if (headers_sent()) return;

        $path = self::VIEWS_PATH . $name . '.php';

        if (!file_exists($path)) throw new NotFoundException("The view $name does not exist.");
        if (!is_readable($path)) throw new FileException("The file $path is not readable.");

        if ($data) extract($data);
        require_once $path;
        if ($data) unset($data);
    }
}
