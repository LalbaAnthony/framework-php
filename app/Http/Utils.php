<?php

namespace App\Http;

use Exception;
use App\Exceptions\NotFoundException;
use App\Exceptions\FileException;

trait Utils
{
    use Html;

    const VIEWS_PATH = __DIR__ . '/../../ressources/views/';
    const ROUTING_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];

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
     * Prepare response headers
     * 
     * @param int $code
     * @param array $headers
     * @return void
     */
    private function prepare(int $code, array $headers = ['methods' => self::ROUTING_METHODS, 'origin' => '*', 'cache' => 0]): void
    {
        if (headers_sent()) return;

        http_response_code($code);

        header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

        if (!empty($headers)) {
            if (isset($headers['methods'])) {
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
    }

    /**
     * Send a JSON response
     * 
     * @param int $code
     * @param mixed $data
     * @param array $headers
     * @return void
     */
    public function json(mixed $data = null, int $code = 200, array $headers = ['methods' => self::ROUTING_METHODS, 'origin' => '*', 'cache' => 0]): void
    {
        $this->prepare($code, $headers);

        header("Content-type: application/json; charset=utf-8");

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
    public function view(string $name, mixed $data = [], int $code = 200): void
    {
        $this->prepare($code);

        $path = self::VIEWS_PATH . $name . '.php';

        if (!file_exists($path)) throw new NotFoundException("The view $name does not exist.");
        if (!is_readable($path)) throw new FileException("The file $path is not readable.");

        self::openHtml();

        if ($data) {
            extract($data, EXTR_OVERWRITE);
        }

        require $path;

        if ($data) {
            foreach (array_keys($data) as $key) {
                unset($$key);
            }
        }

        self::closeHtml();

        exit;
    }
}
