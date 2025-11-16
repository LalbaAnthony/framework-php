<?php

namespace App\Http;

class Request
{
    public string $method;
    public string $uri;
    public string $body;
    public array $params;
    public array $patterns;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = str_replace(APP_ROOT, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->body = file_get_contents('php://input');
        $this->params = $_GET;
    }

    /**
     * @return mixed
     */
    public function json(): mixed
    {
        return json_decode($this->body, true);
    }
}
