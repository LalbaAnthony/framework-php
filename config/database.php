<?php

/**
 * Database settings
 */

define('DATABASE_USER', (string) $_envs['DATABASE_USER'] ?? '');
define('DATABASE_PASSWORD', (string) $_envs['DATABASE_PASSWORD'] ?? '');
define('DATABASE_HOST', (string) $_envs['DATABASE_HOST'] ?? '');
define('DATABASE_NAME', (string) $_envs['DATABASE_NAME'] ?? '');
