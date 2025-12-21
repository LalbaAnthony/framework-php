<?php

namespace App\Http;

class Request
{
    use Utils;

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
        $this->body = $_POST;
        $this->params = $_GET;
        $this->method = $this->extractMethod();
        $this->uri = $this->extractURI();
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->params, $this->body);
    }

    /**
     * @return string
     */
    private function extractMethod(): string
    {
        // Check if there a `_method` override in body (for PUT, DELETE, PATCH)
        if (isset($this->body[self::FORM_METHOD_KEY]) && self::verifyMethod($this->body[self::FORM_METHOD_KEY])) {
            $method = strtoupper($this->body[self::FORM_METHOD_KEY]);
            return $method;
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    private function extractURI(): string
    {
        return str_replace(APP_ROOT, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }
}
