<?php

namespace app\controllers;

use app\controllers\AppController;
use app\models\User;
use app\models\Order;
use sw\App;

class CartController extends AppController
{
    public function addAction()
    {
        $lang = App::$app->getProperty('language');
        $id = get('id');
        $qty = get('qty');
        
        if (!$id) {
            return false;
        }

        $product = $this->model->getProduct($id, $lang);
        if (!$product) {
            return false;
        }

        $this->model->addToCart($product, $qty);
        if ($this->isAjax()) {
            $this->loadView('cart_modal');
        } 
        
        redirect();
    }

    public function showAction()
    {;
        $this->loadView('cart_modal');
    }

    public function deleteAction()
    {
        $id = get('id');
        if (isset($_SESSION['cart'][$id])) {
            $this->model->deleteItem($id);
        }

        if ($this->isAjax()) {
            $this->loadView('cart_modal');
        } 

        redirect();
    }

    public function clearAction(): bool
    {
        if (empty($_SESSION['cart'])) {
            return false;
        } else {
            $this->model->clearCart();
            $this->loadView('cart_modal');
            return true;
        }
    }

    public function viewAction()
    {
        $this->setMeta(___('tpl_cart_title'));
    }

    public function checkoutAction()
    {
        if (!empty($_POST)) {
            // registering a user if he is not authorized
            if (!User::checkAuth()) {
                $user = new User();
                $data = $_POST;
                $user->load($data);
                if (!$user->validate($data) || !$user->checkUnique(___('cart_checkout_error_email_unique'))) {
                    $user->getErrors(); 
                    $_SESSION['form_data'] = $data;
                    redirect();
                } else {
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                    if (!$user_id = $user->save('user')) {
                        $_SESSION['errors'] = ___('cart_checkout_error_register');
                        redirect();
                    }
                }
            }

            // save the order
            $data['user_id'] = $user_id ?? $_SESSION['user']['id'];
            $data['note'] = post('note', 's');
            $user_email = $_SESSION['user']['email'] ?? post('email', 's');

            if (!$order_id = Order::saveOrder($data)) {
                $_SESSION['errors'] = ___('cart_checkout_error_save_order');
            } else {
                Order::mailOrder($order_id, $user_email, 'mail_order_user');
                Order::mailOrder($order_id, App::$app->getProperty('admin_email'), 'mail_order_admin');
                unset($_SESSION['cart']);
                unset($_SESSION['cart.qty']);
                unset($_SESSION['cart.sum']);
                $_SESSION['success'] = ___('cart_checkout_order_success');
            }
        }

        redirect();
    }
}
