<?php

namespace app\controllers\admin;

use sw\App;
use RedBeanPHP\R;
use sw\Pagination;
use sw\Cache;

/** @property Page $model */
class PageController extends AppController
{
    public function indexAction()
    {
        $lang = App::$app->getProperty('language');
        $page = get('page');
        $perpage = 30;
        $total_page = R::count('page');
        $pagination = new Pagination($page, $perpage, $total_page);
        $start = $pagination->getStart();

        $pages = $this->model->getAllPages($lang, $start, $perpage);
        $title = 'All pages';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'pages', 'pagination', 'total_page'));
    }

    public function addAction()
    {
        if (!empty($_POST)) {
            if ($this->model->pageValidate()) {
                if ($this->model->savePage()) {
                    $_SESSION['success'] = 'New page created';
                } else {
                    $_SESSION['errors'] ='Error while creating page';
                }
            }

            redirect();
        }

        $title = 'New page';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title'));
    }

    public function deleteAction()
    {
        $all_lang = App::$app->getProperty('languages');
        $page_id = get('id');

        if (!$this->model->deletePage($page_id)) {
            $_SESSION['errors'] ='Error while deleting page';
            redirect();
        }

        // It's not obligatory. You can display a message that the deletion was successful 
        // and ask the administrator to manually delete cahe of page
        $cache = Cache::getInstance();
        foreach ($all_lang as $code => $item) {
            if (!$cache->delete("shop_page_menu_{$code}")) {
                $_SESSION['errors'] = "The page has been deleted but an error has while deleting cache of page for {$code}";
                redirect();
            };
        }

        $_SESSION['success'] = 'Page removed';
        redirect();
    }
}
