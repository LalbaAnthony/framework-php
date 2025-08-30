<?php

/**
 * App settings
 */
define('APP_NAME_LONG', 'My Website');
define('APP_NAME_SHORT', 'MW');
define('APP_NAME_URL', 'mywebsite.com');
define('APP_DESCRIPTION', 'This is my website description.');
define('APP_AUTHOR', 'Anthony Lalba');
define('APP_CENSORSHIP', false);
define('APP_VERSION', '3.0.1');
define('APP_LANG', 'fr');

/**
 * App configuration
 */
define('APP_ROOT', '/projects/framework-php');
define('APP_URL', 'http://localhost/projects/framework-php');
define('APP_DEBUG', true);
define('APP_ENV', 'development'); // 'development' or 'production'

/**
 * Database settings
 */
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_NAME', 'framework-php');

/**
 * PHP settings
 */
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
date_default_timezone_set('Europe/Paris');