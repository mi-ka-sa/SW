<?php

namespace app\models;

use RedBeanPHP\R;
use sw\Model;

class AppModel extends Model
{
    // public static function createSlug($str_slug)
    public static function createSlug($table, $field, $str, $id): string
    {
        if (!self::onlyEN($str)) {
            $_SESSION['errors'] = 'String must contain only latin characters and numbers';
            redirect();
        }

        $str = self::strToUrl($str);
        $res = R::findOne($table, "$field = ? ", [$str]);

        if (!$res) {
            return $str;
        }

        $str = $str . '-'. $id;
        $res = R::findOne($table, "$field = ? ", [$str]);

        if (!$res) {
            return $str;
        }

        return self::createSlug($table, $field, $str, $id);
    }

    public static function strToUrl($str): string
    {
        $str = strtolower($str);
        $str = preg_replace('~[\s_]~u', '-', $str);
        return $str;
    }

    public static function onlyEN($str): bool
    {
        $chr_en = "a-zA-Z0-9\s`~!@#$%^&*()_+-={}|:;<>?,.\/\"\'\\\[\]";
        if (preg_match("/^[$chr_en]+$/", $str)) {
            return true;
        }
        return false;
    }
    
}
