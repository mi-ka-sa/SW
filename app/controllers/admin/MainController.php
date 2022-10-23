<?php

namespace app\controllers\admin;

class MainController extends AppController
{
    public function indexAction()
    {
        $title = 'Main page';
        $this->setMeta('Admin Panel::Main page');
        $this->setData(compact('title'));
    }
}
