<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Page extends AppModel
{
    public function getAllPages($lang, $start, $perpage): array
    {
        return R::getAll("SELECT p.*, pd.title FROM page p
                        JOIN page_desc pd
                        ON p.id = pd.page_id
                        WHERE pd.language_id = ?
                        LIMIT $start, $perpage",
                        [$lang['id']]);
    }

    public function deletePage($id): bool
    {
        R::begin();
        try {
            $page = R::load('page', $id);
            if (!$page) {
                return false;
            }
            R::trash($page);
            R::exec("DELETE FROM page_desc WHERE page_id = ?", [$id]);
            
            R::commit();
            return true;
        } catch (\Exception $e) {
            R::rollback();
            return false;
        }
    }

    public function pageValidate(): bool
    {
        $errors = '';

        foreach ($_POST['page_desc'] as $lang_id => $item) {
            $item['title'] = trim($item['title']);
            $item['content'] = trim($item['content']);

            if (empty($item['title']) || empty($item['content'])) {
                $errors .= "Content/Title field must not be empty in tab '{$item['lang_code']}'<br>";
            }
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            return false;
        }

        return true;
    }

    public function savePage()
    {
        R::begin();
        try {
            // insert into PAGE
            $page = R::dispense('page');
            $page_id = R::store($page);
            $page->slug = AppModel::createSlug('page', 'slug', $_POST['page_desc'][2]['title'], $page_id);
            R::store($page);
            
            // insert into PAGE_DESC
            foreach ($_POST['page_desc'] as $lang_id => $item ) {
                R::exec("INSERT INTO page_desc (page_id, language_id, title, content,keywords, description) VALUES (?,?,?,?,?,?)",
                [
                    $page_id,
                    $lang_id,
                    $item['title'],
                    $item['content'],
                    $item['keywords'],
                    $item['description'],
                ]);
            }

            R::commit();
            return true;
        } catch (\Exception $e) {
            R::rollback();
            $_SESSION['form_data'] = $_POST;
            return false;
        }
    }

    public function updatePage($id): bool
    {
        R::begin();
        try {
            // update info in PAGE
            $page = R::load('page', $id);
            if (!$page) {
                return false;
            }
            
            // update info in PAGE_DESC
            foreach ($_POST['page_desc'] as $lang_id => $item ) {
                R::exec("UPDATE page_desc SET title = ?, content = ?, keywords = ?, description = ? 
                WHERE page_id = ?
                AND language_id = ?",
                [
                    $item['title'],
                    $item['content'],
                    $item['keywords'],
                    $item['description'],
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

    public function getOnePage($id): false|array
    {
        return R::getAssoc("SELECT pd.language_id, pd.*, p.* FROM page_desc pd
                            JOIN page p
                            ON p.id = pd.page_id
                            WHERE pd.page_id = ?",
                            [$id]);
    }
}
