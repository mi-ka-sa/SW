<?php

namespace sw;

class Language
{
    // array with all translated words of page
    public static array $lang_data = [];
    // an array with all the translated words of template
    public static array $lang_layout = [];
    // an array with all the translated words of view
    public static array $lang_view = [];

    public static function load($lang_code, $route)
    {
        $lang_layout = APP . "/languages/{$lang_code}.php";
        $lang_view = APP . "/languages/{$lang_code}/{$route['controller']}/{$route['action']}.php";

        if (file_exists($lang_layout)) {
            self::$lang_layout = require_once $lang_layout;
        }

        if (file_exists($lang_view)) {
            self::$lang_view = require_once $lang_view;
        }

        self::$lang_data = array_merge(self::$lang_layout, self::$lang_view);
    }

    public static function get($key)
    {
        return self::$lang_data[$key] ?? $key;
    }
}
