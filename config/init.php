<?php

define('DEBUG', 1); //1 - all errors; 0 - hide errors
define('ROOT', dirname(__DIR__));
define('WWW', ROOT . '/public');
define('APP', ROOT . '/app');
define('CORE', ROOT . '/vendor/sw');
define('HELPERS', ROOT . '/vendor/sw/helpers');
define('CACHE', ROOT . '/tmp/cache');
define('LOGS', ROOT . '/tmp/logs');
define('CONFIG', ROOT . '/config');
define('LAYOUT', 'shop');
define('PATH', 'http://abc.local'); // change to your's URL
define('ADMIN', 'http://abc.local/admin');
define('NO_IMAGE', '/public/uploads/no_image.jpg');

require_once ROOT . '/vendor/autoload.php';
