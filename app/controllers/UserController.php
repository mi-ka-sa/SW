<?php

namespace app\controllers;

use app\models\User;
use sw\App;
use sw\Pagination;

/** @property User $model */
class UserController extends AppController
{
    public function credentialsAction()
    {
        if (!User::checkAuth()) {
            redirect(base_url() . 'user/login');
        }

        if (!empty($_POST)) {
            $this->model->load();

            if (empty($this->model->attributes['password'])) {
                unset($this->model->attributes['password']);
            }
            unset($this->model->attributes['email']);
            
            if (!$this->model->validate($this->model->attributes)) {
                $this->model->getErrors();
            } else {
                if (!empty($this->model->attributes['password'])) {
                    $this->model->attributes['password'] = password_hash($this->model->attributes['password'], PASSWORD_DEFAULT);
                }

                if ($this->model->update('user', $_SESSION['user']['id'])) {
                    $_SESSION['success'] = ___('user_credentials_success');
                    foreach ($this->model->attributes as $key => $val) {
                        if (!empty($val) && $key != 'password') {
                            $_SESSION['user'][$key] = $val;
                        }
                    }
                } else {
                    $_SESSION['errors'] = ___('user_credentials_error');
                }
                
            }
            redirect();
        }

        $this->setMeta(___('user_credentials_title'));
    }

    public function signupAction()
    {
        if (User::checkAuth()) {
            redirect(base_url());
        }

        if (!empty($_POST)) {
            $this->model->load();
            if (!$this->model->validate($this->model->attributes) || !$this->model->checkUnique()) {
                $this->model->getErrors();
                $_SESSION['form_data'] = $this->model->attributes;
            } else {
                $this->model->attributes['password'] = password_hash($this->model->attributes['password'], PASSWORD_DEFAULT);
                $id_new_user = $this->model->save('user');
                if ($id_new_user) {
                    // login to the newly created account
                    $_SESSION['user']['id'] = $id_new_user;
                    foreach ($this->model->attributes as $key => $val) {
                        if ($key == 'password') {
                            continue;
                        }
                        $_SESSION['user'][$key] = $val;
                    }
                    $_SESSION['success'] = ___('user_signup_success_register');
                    redirect(base_url() . 'user/cabinet');
                } else {
                    $_SESSION['errors'] = ___('user_signup_error_register');
                }
                
            }
            redirect();
        }

        $this->setMeta(___('tpl_signup'));
    }

    public function loginAction()
    {
        if (User::checkAuth()) {
            redirect(base_url());
        }

        if (!empty($_POST)) {
            if ($this->model->login()) {
                $_SESSION['success'] = ___('user_login_success_login');
                redirect(base_url());
            } else {
                $_SESSION['errors'] = ___('user_login_error_login');
                redirect();
            }
        }

        $this->setMeta(___('tpl_login'));
    } 

    public function logoutAction()
    {
        if (User::checkAuth()) {
            unset($_SESSION['user']);
            redirect(base_url() . 'user/login');
        }
    }

    public function cabinetAction()
    {
        if (!User::checkAuth()) {
            redirect(base_url() . 'user/login');
        }

        redirect(base_url() . 'user/orders');
    }

    public function ordersAction()
    {
        if (!User::checkAuth()) {
            redirect(base_url() . 'user/login');
        }

        $page = get('page');
        $perpage = $this->getActualPerpage();
        $total = $this->model->getCountOrder($_SESSION['user']['id']);
        $pagination = new Pagination($page, $perpage, $total);
        $start_from = $pagination->getStart();

        $orders = $this->model->getUserOrders($start_from, $perpage, $_SESSION['user']['id']);

        $this->setMeta(___('user_orders_title'));
        $this->setData(compact('orders', 'pagination', 'total'));
    }

    public function orderAction()
    {
        if (!User::checkAuth()) {
            redirect(base_url() . 'user/login');
        }

        $id_order = get('id');
        $order = $this->model->getUserOneOrder($id_order);
        if (!$order) {
            throw new \Exception('Not found order with id: ' . $id_order, 404);
        }

        $this->setMeta(___('user_order_title'));
        $this->setData(compact('order'));
    }

    public function filesAction()
    {
        if (!User::checkAuth()) {
            redirect(base_url() . 'user/login');
        }

        $user_id = $_SESSION['user']['id'];
        $lang = App::$app->getProperty('language');
        $page = get('page');
        $perpage = $this->getActualPerpage();
        $total = $this->model->getCountFiles($user_id);
        $pagination = new Pagination($page, $perpage, $total);
        $start_from = $pagination->getStart();

        $files = $this->model->getUserFiles($user_id, $start_from, $perpage, $lang);
        $this->setMeta(___('user_files_title'));
        $this->setData(compact('files', 'pagination', 'total'));
    }

    public function downloadAction()
    {
        if (!User::checkAuth()) {
            redirect(base_url() . 'user/login');
        }

        $user_id = $_SESSION['user']['id'];
        $id = get('id');
        $lang = App::$app->getProperty('language');
        $file = $this->model->getUserOneFile($user_id, $id, $lang);
        if ($file) {
            $path = WWW . "/downloads/{$file['filename']}";
            if (file_exists($path)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($file['original_name']) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($path));
                readfile($path);
                exit();
            } else {
                $_SESSION['errors'] = ___('user_download_error');
            }
        }
        redirect();
    }
}