<?php

/**
 * App settings
 */

define('APP_NAME_LONG', (string) $_envs['APP_NAME_LONG'] ?? '');
define('APP_NAME_SHORT', (string) $_envs['APP_NAME_SHORT'] ?? '');
define('APP_NAME_URL', (string) $_envs['APP_NAME_URL'] ?? '');
define('APP_DESCRIPTION', (string) $_envs['APP_DESCRIPTION'] ?? '');
define('APP_AUTHOR', (string) $_envs['APP_AUTHOR'] ?? '');
define('APP_VERSION', (string) $_envs['APP_VERSION'] ?? '');
define('APP_LANG', (string) $_envs['APP_LANG'] ?? '');

/**
 * App configuration
 */

define('APP_ROOT', (string) $_envs['APP_ROOT'] ?? '/');
define('APP_URL', (string) $_envs['APP_URL'] ?? '/');
define('APP_ENV', (string) $_envs['APP_ENV'] ?? 'production');
define('APP_DEBUG', (defined('APP_ENV') && APP_ENV === 'development') ? true : false);
