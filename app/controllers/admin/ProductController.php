<?php

namespace app\controllers\admin;

use sw\App;
use RedBeanPHP\R;
use sw\Pagination;

class ProductController extends AppController
{
    public function indexAction()
    {
        $lang = App::$app->getProperty('language');

        $page = get('page');
        $perpage = 50;
        $total_product = R::count('product');
        $pagination = new Pagination($page, $perpage, $total_product);
        $start = $pagination->getStart();

        $products = $this->model->getProducts($lang, $start, $perpage);
        $title = 'All products';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'products', 'pagination', 'total_product'));
    }
}
