<?php

namespace App\Http;

class Route
{
    public string $path = '';
    public string $type = '';
    public array $hooks = [];

    /**
     * Router constructor.
     * 
     * @param array $routes
     */
    public function __construct(string $path = '', string $type = '', array $hooks = [])
    {
        $this->path = $path;
        $this->type = $type;
        $this->hooks = $hooks;
    }
}
