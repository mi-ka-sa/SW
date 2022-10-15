<?php

namespace app\models;

use RedBeanPHP\R;

class Product extends AppModel
{
    public function getProduct($slug, $lang): array
    {
        return R::getRow("SELECT p.*, pd.* FROM product p JOIN product_desc pd on 
                        p.id = pd.product_id WHERE p.status = 1 AND p.slug = ? AND pd.language_id  = ?",
                        [$slug, $lang['id']]);
    }

    public function getGallery($prod_id): array
    {
        return R::getAll("SELECT * FROM product_gallery WHERE product_id = ?", [$prod_id]);
    }
}
