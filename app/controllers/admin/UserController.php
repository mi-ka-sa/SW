<?php

namespace app\controllers\admin;

use RedBeanPHP\R;
use sw\Pagination;

/** @property User $model */
class UserController extends AppController
{
    public function indexAction()
    {
        $page = get('page');
        $perpage = 30;
        $total_user = R::count('user');
        $pagination = new Pagination($page, $perpage, $total_user);
        $start = $pagination->getStart();

        $users = $this->model->getAllUsers($start, $perpage);

        $title = 'All users';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'users', 'pagination', 'total_user'));
    }

    public function viewAction()
    {
        $id_user = get('id');
        $user = $this->model->getUser($id_user);
        if (!$user) {
            throw new \Exception('Not found user', 404);
        }

        $page = get('page');
        $perpage = 30;
        $total_orders = $this->model->getCountOrder($id_user);
        $pagination = new Pagination($page, $perpage, $total_orders);
        $start = $pagination->getStart();

        $orders = $this->model->getUserOrders($start, $perpage, $id_user);
        
        $title = 'Profile of user';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'user', 'orders', 'pagination', 'total_orders'));
    }
    
    public function loginAdminAction()
    {
        if ($this->model::isAdmin()) {
            redirect(ADMIN);
        }

        $this->layout = 'login';

        if (!empty($_POST)) {
            if ($this->model->login(true)) {
                $_SESSION['success'] = 'Аuthorization passed successfully';
                redirect(ADMIN);
            } else {
                $_SESSION['errors'] = 'Login/password entered incorrectly';
                redirect();
            }

        }
    }

    public function logoutAction()
    {
        if ($this->model::isAdmin()) {
            unset($_SESSION['user']);
        }
        redirect(ADMIN . '/user/login-admin');
    }
}
