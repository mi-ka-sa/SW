<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Category extends AppModel
{
    public function deleteCategory($id_cat)
    {
        R::begin();
        try{
            if (R::count('category', 'parent_id = ?', [$id_cat])) {
                throw new \Exception('Errors: there are descendants in this category');
            }

            if (R::count('product', 'category_id = ?', [$id_cat])) {
                throw new \Exception('Errors: there are products in this category');
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

    public function categoryValidate(): bool
    {
        $errors = '';
        foreach ($_POST['category_desc'] as $lang_id => &$item) {
            $item['title'] = trim($item['title']);
            if (empty($item['title'])) {
                $errors .= "The 'Category name' field must not be empty in '{$item['lang_code']}'<br>";
            }
            unset($item['lang_code']);
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;

            return false;
        }

        return true;
    }

    public function saveCategory(): bool
    {
        R::begin();
        try {
            $category = R::dispense('category');
            $category->parent_id = post('parent_id');
            $category_id = R::store($category);
            $category->slug = AppModel::createSlug('category', 'slug', $_POST['category_desc'][2]['title'], $category_id);
            R::store($category);
            
            foreach ($_POST['category_desc'] as $lang_id => $item ) {
                R::exec("INSERT INTO category_desc (category_id, language_id, title, description, keywords, content) VALUES (?,?,?,?,?,?)",
                [
                    $category_id,
                    $lang_id,
                    $item['title'],
                    $item['description'],
                    $item['keywords'],
                    $item['content'],
                ]);
            }
            R::commit();
            return true;
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
    }

    public function updateCategory($id): bool
    {
        R::begin();
        try {
            $category = R::load('category', $id);
            if (!$category) {
                return false;
            }
            $category->parent_id = post('parent_id');
            R::store($category);
            
            foreach ($_POST['category_desc'] as $lang_id => $item ) {
                R::exec("UPDATE category_desc SET title = ?, description = ?, keywords = ?, content = ? 
                WHERE category_id = ?
                AND language_id = ?",
                [
                    $item['title'],
                    $item['description'],
                    $item['keywords'],
                    $item['content'],
                    $id, 
                    $lang_id,
                ]);
            }
            R::commit();
            return true;
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
    }

    public function getCategotyInfo($id): array
    {
        return R::getAssoc("SELECT cd.language_id, cd.*, c.* FROM category_desc cd
                            JOIN category c
                            ON c.id = cd.category_id
                            WHERE cd.category_id = ?",
                            [$id]);
    }
}
