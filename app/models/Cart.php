<?php

namespace app\models;

use RedBeanPHP\R;

class Cart extends AppModel
{
    public function getProduct($id, $lang): array
    {
        return R::getRow("SELECT p.*, pd.* FROM  product p JOIN product_desc pd
                        on p.id = pd.product_id WHERE p.status = 1 AND p.id = ? AND language_id = ?",
                        [$id, $lang['id']]);
    }

    public function addToCart($product, $qty = 1)
    {
        $qty = abs($qty);

        if($product['is_download'] && isset($_SESSION['cart'][$product['id']])) {
            return false;
        }

        if (isset($_SESSION['cart'][$product['id']])) {
            $_SESSION['cart'][$product['id']]['qty'] += $qty;
        } else {
            if ($product['is_download']) {
                $qty = 1;
            }
            $_SESSION['cart'][$product['id']] = [
                'title' => $product['title'],
                'slug' => $product['slug'],
                'price' => $product['price'],
                'img' => $product['img'],
                'is_download' => $product['is_download'],
                'qty' => $qty,
            ];
        }

        $_SESSION['cart.qty'] = !empty( $_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.sum'] = !empty( $_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * $product['price'] : $qty * $product['price'];
        return true;
    }

    public function deleteItem($id)
    {
        $qty_minus = $_SESSION['cart'][$id]['qty'];
        $sum_minus = $qty_minus * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.qty'] -= $qty_minus;
        $_SESSION['cart.sum'] -= $sum_minus;

        unset($_SESSION['cart'][$id]);
    }

    public function clearCart()
    {
        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.sum']);
    }

    public static function translateItemCart($lang)
    {
        if (empty($_SESSION['cart'])) {
            return;
        }

        $ids = implode(',', array_keys($_SESSION['cart']));
        $products = R::getAll("SELECT p.id, pd.title FROM product p JOIN product_desc pd 
                            on p.id = pd.product_id WHERE p.id IN ($ids) AND pd.language_id = ?",
                            [$lang['id']]);
        foreach ($products as $one_product) {
            $_SESSION['cart'][$one_product['id']]['title'] = $one_product['title'];
        }
    }
}
