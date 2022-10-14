<?php

namespace app\controllers;

use app\models\Cart;
use sw\App;

class LanguageController extends AppController
{
    public function changeAction()
    {
        $lang = get('lang', 's');
        if ($lang) {
            if (array_key_exists($lang, App::$app->getProperty('languages'))) {
                // cut base URL
                $url = trim(str_replace(PATH, '', $_SERVER['HTTP_REFERER']), '/');

                // break it down into 2 parts, 1st part possible former language
                $url_parts = explode('/', $url, 2);
                // debug('http://abc.local/product/apple');
                // debug($url_parts);
                // serch 1st part (former language) in languages array
                if (array_key_exists($url_parts[0], App::$app->getProperty('languages'))) {
                    // assign a new language to the 1st part, if it is not the base language
                    if ($lang != App::$app->getProperty('language')['code']) {
                        $url_parts[0] = $lang;
                    } else {
                        // if this is the base language - remove the language from the url
                        array_shift($url_parts);
                    }
                } else {
                    // assign a new language to the 1st part, if it is not the base language
                    if ($lang != App::$app->getProperty('language')['code']) {
                        array_unshift($url_parts, $lang);
                    }
                }

                Cart::translateItemCart(App::$app->getProperty('languages')[$lang]);

                $url = PATH . '/' . implode('/', $url_parts);
                redirect($url);
            }
        }
        redirect();
    }
}
