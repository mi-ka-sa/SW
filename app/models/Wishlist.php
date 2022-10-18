<?php

namespace app\models;

use RedBeanPHP\R;

class Wishlist extends AppModel
{
    public function getProduct($id): array|null|string
    {
        return R::getCell("SELECT id FROM product WHERE status = 1 AND id = ?", [$id]);
    }

    public function addToWishlist($id)
    {
        // get Wishlist from cookie
        $wishlist = self::getWishlistIds();

        if (!$wishlist) {
            setcookie('wishlist', $id, time() + 3600*24*7*30, '/');
        } else {
            if (!in_array($id, $wishlist)) {
                if (count($wishlist) > 20) {
                    array_shift($wishlist);
                }

                $wishlist[] = $id;
                $wishlist = implode(',', $wishlist);
                setcookie('wishlist', $wishlist, time() + 3600*24*7*30, '/');
            }
        }
    }

    public static function getWishlistIds(): array
    {
        $wishlist = $_COOKIE['wishlist'] ?? '';

        if ($wishlist) {
            $wishlist = explode(',', $wishlist);
        }

        if (is_array($wishlist)) {
            $wishlist = array_slice($wishlist, 0, 21);
            $wishlist = array_map('intval', $wishlist);
            return $wishlist;
        }

        return [];
    }

    public function getWishlistProducts($lang): array
    {
        $wishlist = self::getWishlistIds();
        if ($wishlist) {
            $wishlist = implode(',', $wishlist);

            return R::getAll("SELECT p.*, pd.* FROM product p JOIN product_desc pd
                    ON p.id = pd.product_id WHERE p.status = 1 AND p.id IN ($wishlist)
                    AND pd.language_id = ? LIMIT 20", [$lang['id']]);
        }
        return [];
    }

    public function deleteFromWishlist($id): bool
    {
        $wishlist = self::getWishlistIds();
        $key = array_search($id, $wishlist);
        if ($key !== false) {
            unset($wishlist[$key]);
            if ($wishlist) {
                $wishlist = implode(',', $wishlist);
                setcookie('wishlist', $wishlist, time() + 3600*24*7*30, '/');
            } else {
                setcookie('wishlist', '', time() - 3600, '/');
            }
            return true;
        }
        return false;
    }
}
