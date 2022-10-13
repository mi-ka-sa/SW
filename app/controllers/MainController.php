<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;

class MainController extends AppController
{
    public function indexAction()
    {
        $slides = R::findAll('slider');

        $products = $this->model->getHitProduct(1, 6);
        // debug($products, 1);

        $this->setData(compact('slides', 'products'));
    }
}
