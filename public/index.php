<?php

// checking of version  PHP
if (PHP_MAJOR_VERSION < 8) {
    die("Error: Need version PHP8 and above");
}


require_once dirname(__DIR__) . '/config/init.php';

new \sw\App();

// echo \sw\App::$app->getProperty('site_name');
// var_dump(\sw\App::$app->getProperties());

// throw new Exception('Возникла ошибочка', 404);

// echo 'Go';

