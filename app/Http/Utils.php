<?php

namespace App\Http;


trait Utils
{    
    const HTTP_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
    const FORM_METHOD_KEY = '_method';

    /**
     * Verify if the HTTP method is valid
     * 
     * @param string $method
     * @return bool
     */
    public static function verifyMethod(string $method): bool
    {
        $method = strtoupper($method);

        if (strlen($method) < 2) return false;
        if (!in_array($method, self::HTTP_METHODS)) return false;

        return true;
    }
}
