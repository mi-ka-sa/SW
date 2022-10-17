<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use sw\App;
use sw\Pagination;

class CategoryController extends AppController
{
    public function viewAction()
    {
        $lang = App::$app->getProperty('language');
        // requested category
        $category = $this->model->getCategory($this->route['slug'], $lang);
        
        if (!$category) {
            throw new \Exception("Category: '{$this->route['slug']}' not found", 404);
        }

        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category['id']);

        // get all children of the current category
        $ids = $this->model->getIds($category['id']);

        // if there are no children, then return the current category
        $ids = !$ids ? $category['id'] : $ids . $category['id'];

        $page = get('page');
        $perpage = App::$app->getProperty('pagination');
        $total = $this->model->getCountProducts($ids);
        $pagination = new Pagination($page, $perpage, $total);
        $start_prod_num = $pagination->getStart();
        

        // get all products in the requested category and its descendants
        $products = $this->model->getProducts($ids,  $lang, $start_prod_num, $perpage);

        $this->setMeta($category['title'], $category['description'], $category['keywords']);
        $this->setData(compact('products', 'category', 'breadcrumbs', 'total', 'pagination', 'perpage'));
    }
}
