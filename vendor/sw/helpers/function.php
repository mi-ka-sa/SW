<?php

function debug($data, $die = false)
{
    echo '<pre>' . print_r($data, 1) . '</pre>';
    if ($die) {
        die;
    }
}

function h($str)
{
    return htmlspecialchars($str);
}

function redirect ($http = false) 
{
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
    }
    header("Location: {$redirect}");
    die;
}

function base_url()
{
    return PATH . '/' . (\sw\App::$app->getProperty('lang') ? \sw\App::$app->getProperty('lang') . '/' : '');
}

// $key of GET array
// $type - values 'i'/'f'/'s'
function get($key, $type = 'i')
{
    $param = $key;
    $$param = $_GET[$param] ?? '';
    if ($type == 'i') {
        return (int)$$param;
    } elseif ($type == 'f') {
        return (float)$$param;
    } else {
        return trim($$param);
    }
}

// $key of POST array
// $type - values 'i'/'f'/'s'
function post($key, $type = 'i')
{
    $param = $key;
    $$param = $_POST[$param] ?? '';
    if ($type == 'i') {
        return (int)$$param;
    } elseif ($type == 'f') {
        return (float)$$param;
    } else {
        return trim($$param);
    }
}

function __($key)
{
    echo \sw\Language::get($key);
}


function ___($key)
{
    return \sw\Language::get($key);
}

function get_inf_of_class($obj)
{
    $reflect = new \ReflectionClass($obj);
    debug($reflect->getMethods());
    debug($reflect->getProperties());
    die;
}

function get_cart_icon($id)
{
    if (!empty($_SESSION['cart']) && array_key_exists($id, $_SESSION['cart'])) {
        $icon = '<i class="fas fa-luggage-cart"></i>';
    } else {
        $icon = '<i class="fas fa-shopping-cart"></i>';
    }

    return $icon;
}