<?php

namespace app\controllers\admin;

use RedBeanPHP\R;

class MainController extends AppController
{
    public function indexAction()
    {
        $total_orders = R::count('orders');
        $new_orders = R::count('orders', 'status = 0');
        $users = R::count('user', "role = 'user'");
        $products = R::count('product');
        $title = 'Main page';
        $this->setMeta('Admin Panel::Main page');
        $this->setData(compact('title', 'total_orders', 'new_orders', 'users', 'products'));
    }
}
