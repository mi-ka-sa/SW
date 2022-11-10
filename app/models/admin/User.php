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

    public function getAllUsers($start, $perpage): array
    {
        return R::findAll("user", "LIMIT $start, $perpage");
    }

    public function getUser($id): array
    {
        return R::getRow("SELECT * FROM user WHERE id = ?", [$id]);
    }
}
