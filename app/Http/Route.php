<?php

namespace App\Http;

use App\Exceptions\RoutingException;

class Route
{
    public string $path = '';
    private string $method = 'index';
    private string $controller = '';
    private string $class = '';

    /**
     * Router constructor.
     * 
     * @param array $routes
     */
    public function __construct(string $path = '')
    {
        $this->path = $path;
    }

    /**
     * Verify if the path is correctly formatted
     * 
     * @return bool
     */
    private function verify(): bool
    {
        $isset = isset($this->path) && $this->path;
        $explode = explode('@', $this->path);
        return $isset && count($explode) === 2;
    }

    /**
     * Run the route
     * 
     * @return void
     */
    public function execute(Request $request, mixed $data = null): void
    {
        if (!$this->verify()) {
            throw new RoutingException('The route path is incorrectly formatted. It should be in the format Controller@method', 500);
        }

        list($this->controller, $this->method) = explode('@', $this->path);

        $this->class = 'App\\Controller\\' . $this->controller;

        if (!class_exists($this->class)) {
            throw new RoutingException($this->class . " does not exist. Check namespaces.", 404);
        }

        $controller = new ($this->class)();

        if (!method_exists($controller, $this->method)) {
            throw new RoutingException($this->method . " does not exist in " . $this->class, 404);
        }

        call_user_func([$controller, $this->method], $request, $data);
    }
}
