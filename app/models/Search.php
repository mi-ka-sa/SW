<?php

namespace app\models;

use RedBeanPHP\R;

class Search extends AppModel
{
    public function getCountFindProducts($search_query, $lang): int
    {
        return R::getCell("SELECT COUNT(*) FROM product p JOIN product_desc pd ON
                p.id = pd.product_id WHERE p.status = 1 AND pd.language_id = ? 
                AND pd.title LIKE ?", [$lang['id'], "%{$search_query}%"]);
    }

    public function getFindProduct($search_query, $lang, $start, $perpage): array
    {
        return R::getAll("SELECT p.*, pd.* FROM product p JOIN product_desc pd ON
        p.id = pd.product_id WHERE p.status = 1 AND pd.language_id = ? AND pd.title
        LIKE ? LIMIT $start, $perpage", [$lang['id'], "%{$search_query}%"]);
    }
}
