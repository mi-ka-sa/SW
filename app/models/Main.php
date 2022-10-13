<?php

namespace app\models;

use RedBeanPHP\R;

class Main extends AppModel
{
    public function getHitProduct($lang, $limit): array
    {
        return R::getAll("SELECT p.*, pd.* FROM product p JOIN product_desc pd on 
        p.id = product_id WHERE p.status = 1 AND p.hit = 1 AND pd.language_id = ?
        LIMIT $limit", [$lang]);
    }
}
