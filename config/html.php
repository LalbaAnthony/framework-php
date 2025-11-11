<?php

/**
 * HTML settings
 */

define('HTML_NOJS', false);

define('HTML_SCRIPTS', [
    [
        'src' => APP_URL . '/ressources/js/main.js',
        'defer' => true,
    ],
    [
        'src' => APP_URL . '/ressources/js/helpers.js',
        'defer' => true,
    ]
]);

define('HTML_STYLES', [
    [
        'href' => APP_URL . '/ressources/css/main.css',
        'rel' => 'stylesheet',
    ],
    [
        'href' => 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap',
        'rel' => 'stylesheet',
    ]
]);
