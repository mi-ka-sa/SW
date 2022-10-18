<?php

namespace app\controllers;

use sw\App;
use sw\Pagination;

class SearchController extends AppController
{
    public function indexAction()
    {
        $search_query = get('s', 's');
        $lang = App::$app->getProperty('language');
        $page = get('page');
        $perpage = $this->getActualPerpage();
        $default_perpage = App::$app->getProperty('pagination');
        $total = $this->model->getCountFindProducts($search_query, $lang);
        $pagination = new Pagination($page, $perpage, $total);
        $start_prod_num = $pagination->getStart();

        $products = $this->model->getFindProduct($search_query, $lang, $start_prod_num, $perpage);
        $this->setMeta(___('tpl_search_title'));
        $this->setData(compact('search_query', 'products', 'pagination', 'total', 'default_perpage'));

    }
}
