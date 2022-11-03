<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Product extends AppModel
{
    public function getProducts($lang, $start, $perpage): array
    {
        return R::getAll("SELECT p.*, pd.title FROM product p
                        JOIN product_desc pd
                        ON p.id = pd.product_id
                        WHERE pd.language_id = ?
                        LIMIT $start, $perpage", 
                        [$lang['id']]);
    }

    public function getDownloads($query): array
    {
        $data = [];
        $downloads = R::getAssoc("SELECT download_id, name FROM download_desc
                                WHERE name LIKE ?
                                LIMIT 10",
                                ["%{$query}%"]);
        if ($downloads) {
            $i = 0;
            foreach ($downloads as $id => $title) {
                $data['items'][$i]['id'] = $id;
                $data['items'][$i]['text'] = $title;
                $i++;
            }
        }

        return $data;
    }

    public function productValidate(): bool
    {
        $errors = '';
        if (!is_numeric($_POST['price'])) {
            $errors .= 'Price must be a number<br>';
        }

        if (!is_numeric($_POST['old_price'])) {
            $errors .= 'Old price must be a number<br>';
        }

        foreach ($_POST['product_desc'] as $lang_id => $item) {
            $item['title'] = trim($item['title']);
            $item['short_desc'] = trim($item['short_desc']);
            if (empty($item['title']) || empty($item['short_desc'])) {
                $errors .= "Short description/Title field must not be empty in tab '{$item['lang_code']}'<br>";
            }
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            return false;
        }

        return true;
    }

    public function saveProduct(): bool
    {

        R::begin();
        try {
            // insert into PRODUCT
            $product = R::dispense('product');
            $product->category_id = post('parent_id');
            $product->price = post('price', 'f');
            $product->old_price = post('old_price', 'f');
            $product->status = post('status', 's') ? 1 : 0;
            $product->hit =post('hit', 's') ? 1 : 0;
            $product->img = post('img', 's') ?: NO_IMAGE;
            $product->is_download = post('is_download') ? 1 : 0;
            $product_id = R::store($product);

            $product->slug = AppModel::createSlug('product', 'slug', $_POST['product_desc'][2]['title'], $product_id);
            R::store($product);
            
            // insert into PRODUCT_DESC
            foreach ($_POST['product_desc'] as $lang_id => $item ) {
                R::exec("INSERT INTO product_desc (product_id, language_id, title, content, short_desc, keyword, description) VALUES (?,?,?,?,?,?,?)",
                [
                    $product_id,
                    $lang_id,
                    $item['title'],
                    $item['content'],
                    $item['short_desc'],
                    $item['keywords'],
                    $item['description'],
                ]);
            }

            // insert into PRODUCT_GALLERY if exists
            if (isset($_POST['gallery']) && is_array($_POST['gallery'])) {
                $sql = "INSERT INTO product_gallery (product_id, img) VALUES ";
                foreach ($_POST['gallery'] as $item) {
                    $sql .= "({$product_id}, ?),";
                }
                $sql = rtrim($sql, ',');
                R::exec($sql, $_POST['gallery']);
            }

            // insert into PRODUCT_DOWNLOAD if is download
            if ($product->is_download) {
                $download_id = post('is_download');
                R::exec("INSERT INTO product_download (product_id, download_id) VALUES (?,?)", [$product_id, $download_id]);
            }

            R::commit();
            return true;
        } catch (\Exception $e) {
            R::rollback();
            $_SESSION['form_data'] = $_POST;
            return false;
        }
    }
}
