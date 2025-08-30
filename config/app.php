<?php

/**
 * App settings
 */

define('APP_NAME_LONG', 'My Website');
define('APP_NAME_SHORT', 'MW');
define('APP_NAME_URL', 'mywebsite.com');
define('APP_DESCRIPTION', 'This is my website description.');
define('APP_AUTHOR', 'Anthony Lalba');
define('APP_VERSION', '3.0.1');
define('APP_LANG', 'fr');

/**
 * App configuration
 */

define('APP_ROOT', '/projects/framework-php');
define('APP_URL', 'http://localhost/projects/framework-php');
define('APP_ENV', 'development'); // 'development' or 'production'
define('APP_DEBUG', (defined('APP_ENV') && APP_ENV === 'development') ? true : false);