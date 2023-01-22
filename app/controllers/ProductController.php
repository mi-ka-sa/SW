<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use sw\App;

/** @property Product $model */
class ProductController extends AppController
{
    public function viewAction()
    {
        $lang = App::$app->getProperty('language');

        $product = $this->model->getProduct($this->route['slug'], $lang);
        
        if (!$product) {
            throw new \Exception("Product not foud for link: {$this->route['slug']}", 404);
        } 

        $breadcrumbs = Breadcrumbs::getBreadcrumbs($product['category_id'], $product['title']);

        $gallery = $this->model->getGallery($product['id']);
        $this->setMeta($product['title'], $product['description'], $product['keyword']);
        $this->setData(compact('product', 'gallery', 'breadcrumbs'));
    }
}
