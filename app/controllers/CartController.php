<?php

namespace app\controllers;

use app\controllers\AppController;
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
    {
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
}
