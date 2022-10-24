<?php

namespace app\models\admin;

use sw\Model;
use RedBeanPHP\R;

class Category extends Model
{
    public function deleteCategory($id_cat)
    {
        R::begin();
        try{
            if (R::count('category', 'parent_id = ?', [$id_cat])) {
                throw new \Exception('Errors: there are descendants in this category');
            }

            if (R::count('product', 'category_id = ?', [$id_cat])) {
                throw new \Exception('Errors: Errors: there are products in this category');
            }
            
            R::exec("DELETE FROM category
                        WHERE id = ?",
                        [$id_cat]);

            R::exec("DELETE FROM category_desc
                        WHERE category_id = ?",
                        [$id_cat]);
            
            $_SESSION['success'] = 'Category has been successfully deleted';
            R::commit();
        }
        catch(\Exception $e) {
            R::rollback();
            $_SESSION['errors'] = $e->getMessage();
        }
    }
}
