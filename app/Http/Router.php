<?php

namespace App\Http;

use App\Exceptions\RoutingException;
use App\Component;

class Router
{
    use Utils;
    use Html;

    private Request $request;
    private array $routes = [];
    private array $route = [];

    /**
     * Router constructor.
     * 
     * @param Request $request
     * @param array $routes
     */
    public function __construct(Request $request, array $routes = [])
    {
        $this->request = $request;
        $this->routes = $routes;
    }

    public function setRoute(array $route): void
    {
        $this->route = $route;
    }

    /**
     * Find the route based of URI and method
     * 
     * @return void
     */
    public function find(): void
    {
        $uri = $this->request->uri;
        $method = $this->request->method;

        if (!isset($this->routes[$uri])) {
            throw new RoutingException('Route not found: ' . $uri, 404);
        }

        $route = $this->routes[$uri];

        if (!isset($route[$method])) {
            throw new RoutingException('Method not allowed. Allowed methods: ' . implode(', ', array_keys($route)), 405);
        }

        $this->setRoute($route[$method]);

        if (!isset($this->route['type']) && !in_array($this->route['type'], ['view', 'api'])) {
            throw new RoutingException('Invalid route type', 400);
        }
    }

    /**
     * Run the current route
     * 
     * @return void
     */
    public function call(): void
    {
        if (!$this->route || empty($this->route)) {
            throw new RoutingException('No route found', 500);
        }

        if (!isset($this->route['path']) || !$this->route['path']) {
            throw new RoutingException('No route path found', 400);
        }

        list($controllerPath, $controllerMethod) = explode('@', $this->route['path']);

        if (!$controllerPath || !$controllerMethod) {
            throw new RoutingException('Invalid route path', 400);
        }

        $controllerClass = 'App\\Controller\\' . $controllerPath;

        if (!class_exists($controllerClass)) {
            throw new RoutingException("$controllerClass does not exist. Check namespaces.", 404);
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $controllerMethod)) {
            throw new RoutingException("$controllerMethod does not exist in $controllerClass.", 404);
        }

        call_user_func([$controller, $controllerMethod], $this->request);
    }

    /**
     * Check if the current route has a hook and trigger it
     * 
     * @return void
     */
    public function hook(string $timing = 'before'): void
    {
        if (!isset($this->route['hooks'][$timing]) || !$this->route['hooks'][$timing]) return;

        if (isset($this->route['hooks'][$timing]['components'])) {
            foreach ($this->route['hooks'][$timing]['components'] as $component) {
                Component::display($component, [], ['css' => true]);
            }
        }
    }

    /**
     * Execute the route: open html if view, call hooks, call controller, close html if view
     * 
     * @return void
     */
    public function execute(): void
    {
        if ($this->route['type'] === 'view') self::openHtml();
        $this->hook('before');
        $this->call();
        $this->hook('after');
        if ($this->route['type'] === 'view') self::closeHtml();
    }

    /**
     * Force execute a specific route
     * 
     * @return void
     */
    public function force(array $route): void
    {
        $this->setRoute($route);
        $this->execute();
    }

    /**
     * Dispatch the request to the appropriate controller
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $this->find();
        $this->execute();
    }
}
