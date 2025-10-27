<?php

/**
 * App settings
 */

define('APP_NAME_LONG', $envs['APP_NAME_LONG'] ?? '');
define('APP_NAME_SHORT', $envs['APP_NAME_SHORT'] ?? '');
define('APP_NAME_URL', $envs['APP_NAME_URL'] ?? '');
define('APP_DESCRIPTION', $envs['APP_DESCRIPTION'] ?? '');
define('APP_AUTHOR', $envs['APP_AUTHOR'] ?? '');
define('APP_VERSION', $envs['APP_VERSION'] ?? '');
define('APP_LANG', $envs['APP_LANG'] ?? '');

/**
 * App configuration
 */

define('APP_ROOT', '/projects/framework-php'); // /var/www/html/framework-php
define('APP_URL', 'http://localhost/projects/framework-php');
define('APP_ENV', $envs['APP_ENV'] ?? 'production');
define('APP_DEBUG', (defined('APP_ENV') && APP_ENV === 'development') ? true : false);