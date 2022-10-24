<?php

namespace app\models\admin;

use app\models\User as ModelsUser;

class User extends ModelsUser
{
    public static function isAdmin(): bool
    {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin');
    }
}
