<?php

namespace App\Http;

use App\Exceptions\RoutingException;

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

    /**
     * Set the current route
     * 
     * @param Route $route
     * @return void
     */
    public function setRoute(Route $route): void
    {
        $this->route = $route;
    }

    /**
     * Add a new route
     * 
     * @param Route $route
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
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
     * Force execute a specific route
     * 
     * @return void
     */
    public function force(Route $route, mixed $data = null): void
    {
        $this->setRoute($route);
        $this->route->execute($this->request, $data);
    }

    /**
     * Dispatch the request to the appropriate controller
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $this->setRoute($this->find());
        $this->route->execute($this->request);
    }
}
