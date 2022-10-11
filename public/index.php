<?php

// checking of version  PHP
if (PHP_MAJOR_VERSION < 8) {
    die("Error: Need version PHP8 and above");
}


require_once dirname(__DIR__) . '/config/init.php';
require_once HELPERS . '/function.php';
require_once CONFIG . '/routes.php';

new \sw\App();

debug(\sw\Router::getRoutes());
