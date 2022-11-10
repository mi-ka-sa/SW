<?php

namespace app\models\admin;

use app\models\User as ModelsUser;
use RedBeanPHP\R;

class User extends ModelsUser
{
    public static function isAdmin(): bool
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin');
    }

    public function getAllUsers($start, $perpage)
    {
        return R::findAll('user', "LIMIT $start, $perpage");
    }
}
