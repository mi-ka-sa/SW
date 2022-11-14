<?php

namespace app\controllers\admin;

use sw\App;
use sw\Cache;

class CacheController extends AppController
{
    public function indexAction()
    {
        $title = 'Сache management';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title'));
    }

    public function deleteAction()
    {
        $langs = App::$app->getProperty('languages');
        $cache_key = get('cache', 's');
        $cache = Cache::getInstance();
        $errors = 'Сache deletion error: ';
        switch ($cache_key) {
            case 'category':
                foreach ($langs as $code => $item) {
                    if (!$cache->delete("shop_menu_{$code}")) {
                        $_SESSION['errors'] = $errors . "of category for {$code}";
                        redirect();
                    };
                }
                break;
            
            case 'page':
                foreach ($langs as $code => $item) {
                    if (!$cache->delete("shop_page_menu_{$code}")) {
                        $_SESSION['errors'] = $errors . "of page for {$code}";
                        redirect();
                    };
                }
                break;
        }

        $_SESSION['success'] = 'Cache deleted';
        redirect();
    }
}
