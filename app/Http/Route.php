<?php

namespace App\Http;

class Route
{
    public string $path = '';
    public string $type = '';

    /**
     * Router constructor.
     * 
     * @param array $routes
     */
    public function __construct(string $path = '', string $type = '')
    {
        $this->path = $path;
        $this->type = $type;
    }
}
