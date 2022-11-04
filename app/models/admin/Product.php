<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;
use sw\App;

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

    public function getOneProduct($id): false|array
    {
        $product = R::getAssoc("SELECT pd.language_id, pd.*, p.* FROM product_desc pd 
                                JOIN product p
                                ON p.id = pd.product_id
                                WHERE pd.product_id = ? ",
                                [$id]);
        if (!$product) {
            return false;
        }

        $key = key($product);
        if ($product[$key]['is_download']) {
            $download_inf = $this->getProductDownload($id);
            $product[$key]['download_id'] = $download_inf['download_id'];
            $product[$key]['download_name'] = $download_inf['name'];
        }

        return $product;
    }

    public function getProductDownload($product_id): array
    {
        $lang_id = App::$app->getProperty('language')['id'];
        return R::getRow("SELECT pd.download_id, dd.name FROM product_download pd
                        JOIN download_desc dd
                        ON pd.download_id = dd.download_id
                        WHERE pd.product_id = ?
                        AND dd.language_id = ?", 
                        [$product_id, $lang_id]);
    }

    public function getGallery($product_id): array
    {
        return R::getCol("SELECT img FROM product_gallery
                        WHERE product_id = ?",
                        [$product_id]);
    }

    public function updateProduct($id): bool
    {

        R::begin();
        try {
            // update info in PRODUCT
            $product = R::load('product', $id);
            if (!$product) {
                return false;
            }

            $product->category_id = post('parent_id');
            $product->price = post('price', 'f');
            $product->old_price = post('old_price', 'f');
            $product->status = post('status', 's') ? 1 : 0;
            $product->hit =post('hit', 's') ? 1 : 0;
            $product->img = post('img', 's') ?: NO_IMAGE;
            $product->is_download = post('is_download') ? 1 : 0;
            $product_id = R::store($product);
            
            // update info in PRODUCT_DESC
            foreach ($_POST['product_desc'] as $lang_id => $item ) {
                R::exec("UPDATE product_desc SET title = ?, content = ?, short_desc = ?, keyword = ?, description = ? 
                WHERE product_id = ?
                AND language_id = ?",
                [
                    $item['title'],
                    $item['content'],
                    $item['short_desc'],
                    $item['keyword'],
                    $item['description'],
                    $id,
                    $lang_id,
                ]);
            }

            // update info in PRODUCT_GALLERY if exists
            if (!isset($_POST['gallery'])) {
                R::exec("DELETE FROM product_gallery WHERE product_id = ?", [$id]);
            }

            if (isset($_POST['gallery']) && is_array($_POST['gallery'])) {
                $gallery = $this->getGallery($id);
                
                if (count($gallery) != count($_POST['gallery']) || array_diff($gallery, $_POST['gallery']) || array_diff($_POST['gallery'],$gallery)) {
                    R::exec("DELETE FROM product_gallery WHERE product_id = ?", [$id]);
                    $sql = "INSERT INTO product_gallery (product_id, img) VALUES ";
                    foreach ($_POST['gallery'] as $item) {
                        $sql .= "({$product_id}, ?),";
                    }
                    $sql = rtrim($sql, ',');
                    R::exec($sql, $_POST['gallery']);
                }
            }

            // update info in PRODUCT_DOWNLOAD if is download
            R::exec("DELETE FROM product_download WHERE product_id = ?", [$id]);
            if ($product->is_download) {
                $download_id = post('is_download');
                R::exec("INSERT INTO product_download (product_id, download_id) VALUES (?,?)", [$product_id, $download_id]);
            }

            R::commit();
            return true;
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
    }
}
