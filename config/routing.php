<?php

/**
 * Routing settings
 */

define('ROUTING_ALLOW_METHODS', (bool) $_envs['ROUTING_ALLOW_METHODS'] ?? true);
define('ROUTING_ALLOW_ORIGIN', (string) $_envs['ROUTING_ALLOW_ORIGIN'] ?? '*');
