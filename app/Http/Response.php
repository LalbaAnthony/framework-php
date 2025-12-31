<?php

namespace App\Http;

use App\Http\Router;
use App\Http\Utils;

trait Response
{
    use Utils;

    /**
     * Redirect to a different page
     * 
     * @return void
     */
    public static function redirect(string $uri, array $params = []): void
    {
        if (headers_sent()) return;

        header('Location: ' . Router::buildUrl(APP_URL . $uri, $params));

        exit;
    }

    /**
     * Prepare response headers
     * 
     * @param int $code
     * @param array $headers
     * @return void
     */
    private function prepare(int $code, array $headers = ['cache' => 0]): void
    {
        global $router;

        if (headers_sent()) return;

        http_response_code($code);

        // Remove sensitive headers
        header_remove('X-Powered-By');
        header_remove('X-Frame-Options');
        header_remove('Server');

        // Set allow methods
        if (ROUTING_ALLOW_METHODS) {
            header('Access-Control-Allow-Methods: ' . implode(', ', $router->getAllowedMethods()));
        }

        // Set allow origin
        if (ROUTING_ALLOW_ORIGIN) {
            $allowOrigin = is_array(ROUTING_ALLOW_ORIGIN) ? ROUTING_ALLOW_ORIGIN : [ROUTING_ALLOW_ORIGIN];
            header('Access-Control-Allow-Origin: ' . implode(', ', $allowOrigin));
        }

        if (!empty($headers)) {
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
    public function json(mixed $data = null, int $code = 200, array $headers = ['methods' => self::HTTP_METHODS, 'origin' => '*', 'cache' => 0]): void
    {
        $this->prepare($code, $headers);

        header("Content-type: application/json; charset=utf-8");

        echo json_encode($data);

        exit;
    }
}
