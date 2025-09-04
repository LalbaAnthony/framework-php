<?php

namespace App\Http;

use App\Exceptions\RoutingException;
use App\Component;

class Router
{
    use Utils;
    use Html;

    private Request $request;
    private Route $route;

    /** @var Route[] */
    private array $routes = [];

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

    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    /**
     * Find the route based of URI and method
     * 
     * @return Route
     */
    public function find(): Route
    {
        $uri = $this->request->uri;
        $method = $this->request->method;

        if (!isset($this->routes[$uri])) {
            throw new RoutingException('Route not found: ' . $uri, 404);
        }

        $route = $this->routes[$uri];

        if (!isset($route[$method])) {
            throw new RoutingException('Method not allowed', 405);
        }

        return $route[$method];
    }

    /**
     * Run the current route
     * 
     * @return void
     */
    public function execute(): void
    {
        if (!$this->route || empty($this->route)) {
            throw new RoutingException('No route found', 500);
        }

        if (!isset($this->route->path) || !$this->route->path) {
            throw new RoutingException('No route path found', 400);
        }

        if (!isset($this->route->type) || !$this->route->type) {
            throw new RoutingException('No route type found', 400);
        }

        if (!in_array($this->route->type, self::ROUTE_TYPES)) {
            throw new RoutingException('Invalid route type', 400);
        }

        list($controllerPath, $controllerMethod) = explode('@', $this->route->path);

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
     * Force execute a specific route
     * 
     * @return void
     */
    public function force(Route $route): void
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
        $route = $this->find();
        $this->setRoute($route);
        $this->execute();
    }
}
