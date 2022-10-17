<?php

namespace app\models;

use RedBeanPHP\R;
use sw\App;

class Category extends AppModel
{
    public function getCategory($slug, $lang): array
    {
        return R::getRow("SELECT c.* , cd.* FROM category c JOIN category_desc cd
                    on c.id = cd.category_id WHERE c.slug = ? AND cd.language_id = ?", 
                    [$slug, $lang['id']]);
    }

    // get all children of the current category
    public function getIds($id): string
    {
        $lang = App::$app->getProperty('language')['code'];
        $categories = App::$app->getProperty("categories_{$lang}");

        $ids = '';

        foreach ($categories as $key => $val) {
            if ($val['parent_id'] == $id) {
                $ids .= $key . ',';
                $ids .= $this->getIds($key);
            }
        }

        return $ids;
    }

    public function getProducts($ids, $lang, $start, $perpage): array
    {
        // white list
        $sort_values = [
            'title_asc' => 'ORDER by title ASC',
            'title_desc' => 'ORDER by title DESC',
            'price_asc' => 'ORDER by price ASC',
            'price_desc' => 'ORDER by price DESC',
        ];

        $order_by = '';
        if (isset($_GET['sort']) && array_key_exists($_GET['sort'], $sort_values)) {
            $order_by = $sort_values[$_GET['sort']];
        }
        return R::getAll("SELECT p.*, pd.* FROM product p JOIN product_desc pd
                         on p.id = pd.product_id WHERE p.status = 1 AND p.category_id IN ($ids)
                         AND pd.language_id = ? $order_by LIMIT $start, $perpage", [$lang['id']]);
    }

    public function getCountProducts($ids): int
    {
        return R::count('product', "category_id IN ($ids) AND status = 1");
    }
}
