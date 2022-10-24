<?php

namespace app\models;

use RedBeanPHP\R;

class User extends AppModel
{
    public array $attributes = [
        'email' => '',
        'password' => '',
        'name' => '',
        'address' => '',
    ];

    public array $rules = [
        'required' => ['email', 'password', 'name', 'address'],
        'email' => ['email'],
        'lengthMin' => [
            ['password', 6],
        ],
        'optional' => ['email', 'password'],
    ];

    public array $labels = [
        'email' => 'tpl_signup_email_input',
        'password' => 'tpl_signup_password_input',
        'name' => 'tpl_signup_name_input',
        'address' => 'tpl_signup_address_input',
    ];

    public static function checkAuth(): bool
    {
        return isset($_SESSION['user']);
    }

    public function checkUnique($text_error = ''): bool
    {
        $user = R::findOne('user', 'email = ?', 
            [$this->attributes['email']]);
        if ($user) {
            $this->errors['unique'][] = $text_error ?: ___('user_signup_error_email_unique');
            return false;
        } else {
            return true;
        }
    }

    public function login($is_admin = false): bool
    {
        $email = post('email', 's');
        $pass = post('password', 's');

        if ($email && $pass) {
            if ($is_admin) {
                $user = R::findOne('user', "email = ? AND role = 'admin'", [$email]);
            } else {
                $user = R::findOne('user', "email = ?", [$email]);
            }

            if ($user) {
                if (password_verify($pass, $user->password)) {
                    foreach ($user as $key => $val) {
                        if ($key == 'password') {
                            continue;
                        }
                        $_SESSION['user'][$key] = $val;
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function getCountOrder($user_id): int
    {
        return R::count('orders', 'user_id = ?', [$user_id]);
    }

    public function getUserOrders($start, $perpage, $user_id): array
    {
        return R::getAll("SELECT * FROM orders
                        WHERE user_id = ?
                        ORDER BY id DESC 
                        LIMIT $start, $perpage", [$user_id]);
    }

    public function getUserOneOrder($id): array
    {
        return R::getAll("SELECT o.*, op.*, p.img FROM orders o
                        JOIN order_product op
                        ON o.id = op.order_id
                        JOIN product p
                        ON p.id = op.product_id 
                        WHERE o.id = ?", 
                        [$id]);
    }

    public function getCountFiles($user_id): int
    {
        return R::count('order_download', 'user_id = ? AND status = 1', [$user_id]);
    }

    public function getUserFiles($user_id, $start, $perpage, $lang): array
    {
        return R::getAll("SELECT od.*, d.*, dd.* FROM order_download od
                        JOIN download d
                        ON d.id = od.download_id
                        JOIN download_desc dd
                        ON d.id = dd.download_id
                        WHERE od.user_id = ?
                        AND od.status = 1
                        AND dd.language_id = ?
                        LIMIT $start, $perpage",
                        [$user_id, $lang['id']]);
    }

    public function getUserOneFile($user_id, $file_id, $lang): array
    {
        return R::getRow("SELECT od.*, d.*, dd.* FROM order_download od
                JOIN download d
                ON d.id = od.download_id
                JOIN download_desc dd
                ON d.id = dd.download_id
                WHERE od.user_id = ?
                AND od.status = 1
                AND od.download_id = ?
                AND dd.language_id = ?",
                [$user_id, $file_id, $lang['id']]);
    }
}
