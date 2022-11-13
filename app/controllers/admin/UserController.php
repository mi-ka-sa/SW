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

    public function editAction()
    {
        $id_user = get('id');
        $user = $this->model->getUser($id_user);
        if (!$user) {
            throw new \Exception('Not found user', 404);
        }

        if (!empty($_POST)) {
            $this->model->load();
            if (empty($this->model->attributes['password'])) {
                unset($this->model->attributes['password']);
            }

            if (!$this->model->validate($this->model->attributes) || !$this->model->checkEmail($user)) {
                $this->model->getErrors();
            } else {
                if (!empty($this->model->attributes['password'])) {
                    $this->model->attributes['password'] = password_hash($this->model->attributes['password'], PASSWORD_DEFAULT);
                }
                if ($this->model->update('user', $id_user)) {
                    $_SESSION['success'] = 'Data of user updated successfully';
                } else {
                    $_SESSION['errors'] = 'Error updating user data';
                }
            }
            redirect();
        }

        $title = 'Edit of user';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'user'));
    }

    public function addAction()
    {
        if (!empty($_POST)) {
            $this->model->load();
            if (!$this->model->validate($this->model->attributes) || !$this->model->checkUnique('This mail is already taken')) {
                $this->model->getErrors();
                $_SESSION['form_data'] = $_POST;
            } else {
                $this->model->attributes['password'] = password_hash($this->model->attributes['password'], PASSWORD_DEFAULT);
                if ($this->model->save('user')) {
                    $_SESSION['success'] = 'User added';
                } else {
                    $_SESSION['errors'] = 'Error adding user';
                }
            }
            redirect();
        }

        $title = 'New user';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title'));
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
