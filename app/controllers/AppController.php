<?php

namespace app\controllers;

use app\models\AppModel;
use app\models\Wishlist;
use app\widgets\language\Language;
use sw\App;
use sw\Controller;
use sw\Language as SwLanguage;
use RedBeanPHP\R;

class AppController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route);
        new AppModel();

        App::$app->setProperty('languages', Language::getLanguages());
        App::$app->setProperty('language', Language::getLanguage(App::$app->getProperty('languages')));

        $lang = App::$app->getProperty('language');

        SwLanguage::load($lang['code'], $route);

        $categories = R::getAssoc("SELECT c.*, cd.* FROM category c 
                        JOIN category_desc cd
                        ON c.id = cd.category_id
                        WHERE cd.language_id = ?", [$lang['id']]);

        App::$app->setProperty("categories_{$lang['code']}", $categories);
        App::$app->setProperty('wishlist', Wishlist::getWishlistIds());
        
    }

    public function getActualPerpage()
    {
        $perpage_values = [
            3 => 3,
            50 => 50,
            75 => 75,
            100 => 100,
        ];
        
        if (isset($_GET['on_page']) && array_key_exists($_GET['on_page'], $perpage_values)) {
            $perpage = $perpage_values[$_GET['on_page']]; 
        } else {
            $perpage = App::$app->getProperty('pagination');
        }
        
        return $perpage;
    }
}
