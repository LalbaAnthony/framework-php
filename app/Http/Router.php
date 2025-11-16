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
        $uri = trim($this->request->uri, '/');
        $method = $this->request->method;

        foreach ($this->routes as $pattern => $methods) {

            // Convert pattern to regex
            $regex = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $pattern);
            $regex = '#^' . trim($regex, '/') . '$#';

            if (preg_match($regex, $uri, $matches)) {

                if (!isset($methods[$method])) {
                    throw new RoutingException("Method not allowed", 405);
                }

                $route = $methods[$method];

                // Store extracted parameters inside request
                $this->request->patterns = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);

                return $route;
            }
        }

        throw new RoutingException("Route not found: " . $uri, 404);
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
