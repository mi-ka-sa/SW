<?php

namespace sw;

use Exception;

class Router
{
    protected static array $routes = [];
    protected static array $route = [];

    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute(): array
    {
        return self::$route;
    }

    protected static function removeQueryString($url): string
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false === str_contains('=', $params[0])) {
                return rtrim($params[0], '/');
            }
        }

        return '';
    }

    public static function dispath($url)
    {
        $url = self::removeQueryString($url);
        if (self::matchRoute($url)) {
            if (!empty(self::$route['lang'])) {
                App::$app->setProperty('lang', self::$route['lang']);
            }
            // way with full namespace
            $controller = 'app\controllers\\' . self::$route['admin_prefix'] . self::$route['controller'] . 'Controller'; 
            if (class_exists($controller)) {
                // create an instance of the class
                $controllerObj = new $controller(self::$route);

                $controllerObj->getModel();

                // create name for Action
                $action = self::lowerCamelCase(self::$route['action'] . 'Action');
                if (method_exists($controllerObj, $action)) {
                    // call the method
                    $controllerObj->$action();
                    $controllerObj->getView();
                } else {
                    throw new Exception("Method {$controller}::{$action} not found", 404);
                }
            } else {
                throw new Exception("Controller {$controller} not found", 404);
            }
        } else {
            throw new Exception('Page not found', 404);
        }
    }

    public static function matchRoute($url): bool
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#{$pattern}#", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }
                if (!isset($route['admin_prefix'])) {
                    $route['admin_prefix'] = '';
                } else {
                    $route['admin_prefix'] .= '\\';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }

        return false;
    }

    protected static function upperCamelCase($name): string
    {
        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);

        return $name;
    }

    protected static function lowerCamelCase($name): string
    {
        return lcfirst(self::upperCamelCase($name));
    }
}
