<?php

namespace app\controllers;

use app\models\Main;
use RedBeanPHP\R;
use sw\App;
use sw\Cache;

class MainController extends AppController
{
    public function indexAction()
    {
        $lang = App::$app->getProperty('language');
        $slides = R::findAll('slider');

        $products = $this->model->getHitProduct($lang, 6);

        $this->setData(compact('slides', 'products'));
        $this->setMeta(
            ___('main_index_meta_title'),
            ___('main_index_meta_description'),
            ___('main_index_meta_keywords')
        );
    }
}
