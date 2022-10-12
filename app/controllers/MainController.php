<?php

namespace app\controllers;


use sw\Controller;
use app\models\Main;
use RedBeanPHP\R;

class MainController extends Controller
{
    public function indexAction()
    {
        $names = $this->model->getNames();
        $one_name =R::getRow('SELECT * FROM name WHERE id = 2 ');
        $this->setMeta('Home page', 'Description for home page', 'Keywords for home page');
        $this->setData(compact('names'));
    }
}
