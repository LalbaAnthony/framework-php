<?php

namespace App\Http;

class Request
{
    public string $method;
    public string $uri;
    public array $body;
    public array $params;
    public array $patterns;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = str_replace(APP_ROOT, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $this->body = $_POST;
        $this->params = $_GET;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->params, $this->body);
    }
}
