<?php

namespace App\Http;

use App\Exceptions\RoutingException;

class Router
{
    use Utils;
    use Html;

    private Request $request;
    private Route $route;
    private array $allowedMethods = [];

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
     * Get the allowed methods for the current request.
     * 
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }

    /**
     * Validate HTTP methods.
     * 
     * @param array $methods
     * @return bool
     */
    private static function areMethodsValid(array $methods = []): bool
    {
        if (empty($methods)) return true;

        foreach ($methods as $method) {
            if (!in_array($method, self::HTTP_METHODS)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Set the allowed methods for the current request.
     * 
     * @param array $methods
     * @return void
     */
    public function setAllowedMethods(array $methods, bool $verify = true): void
    {
        if ($verify && !self::areMethodsValid($methods)) {
            throw new RoutingException("Invalid HTTP methods", 500);
        }

        $this->allowedMethods = $methods;
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
     * Get all registered routes
     * 
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Find the route based of URI and method
     * 
     * @return Route|callable
     */
    public function findAndSet(): void
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

                $routeOrCallable = $methods[$method];

                if (is_array($methods)) {
                    $allowedMethods = array_keys($methods);
                    $this->setAllowedMethods($allowedMethods);
                }

                if ($routeOrCallable instanceof Route || is_callable($routeOrCallable)) {
                    $this->setRoute($routeOrCallable);
                }

                // Store extracted parameters inside request
                $this->request->patterns = arrafy_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);

                return;
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
        $this->setAllowedMethods(self::HTTP_METHODS, false);
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
        $this->findAndSet();

        if ($this->route instanceof Route) {
            $this->route->execute($this->request);
            return;
        }

        if (is_callable($this->route)) {
            echo call_user_func($this->route, $this->request);
            return;
        }

        throw new RoutingException("Invalid route handler", 500);
    }

    /**
     * Get the current URL.
     *
     * @return string
     */
    public static function currentUrl(bool $queries = true): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];

        $url = $protocol . $host . $uri;

        if (!$queries) $url = explode('?', $url)[0];

        return $url;
    }

    /**
     * Get the current URL queries.
     *
     * @return array
     */
    public static function currentQueries(): array
    {
        $queries = [];
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $queries);
        }

        return $queries;
    }

    /**
     * Build a URL with query parameters.
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function buildUrl(?string $url, array $params = [], bool $keep = false): string
    {
        if (!$url || empty($url)) $url = self::currentUrl(false);
        if (empty($params)) return $url;

        if ($keep) {
            $currentQueries = self::currentQueries();
            $params = array_merge($currentQueries, $params);
        }

        $query = http_build_query($params);
        return $url . '?' . $query;
    }
}
