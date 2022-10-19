<?php

namespace app\controllers;

use sw\App;

class PageController extends AppController
{
    public function viewAction()
    {
        $lang = App::$app->getProperty('language');
        $page = $this->model->getPage($this->route['slug'], $lang);
        
        if (!$page) {
            throw new \Exception("Page with link {$this->route['slug']} not found", 404);
            return;
        }

        $this->setMeta($page['title'], $page['description'], $page['keywords']);
        $this->setData(compact('page'));
    }
}
