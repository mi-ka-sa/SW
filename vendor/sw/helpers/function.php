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

function get_field_value($name_field)
{
    return isset($_SESSION['form_data'][$name_field]) ? 
        h($_SESSION['form_data'][$name_field]) :
        '';
}

function get_field_array_value($name_field, $key, $index)
{
    return isset($_SESSION['form_data'][$name_field][$key][$index]) ? 
        h($_SESSION['form_data'][$name_field][$key][$index]) :
        '';
}


function current_uri(): string
{
    $uri = trim(urldecode($_SERVER['QUERY_STRING']), '/');
    if ($uri) {
        $params = explode('&', $uri, 2);
        if (false === str_contains('=', $params[0])) {
            return rtrim($params[0], '/');
        }
    }

    return '';
}