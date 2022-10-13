<?php

namespace app\widgets\language;

use RedBeanPHP\R;
use sw\App;

class Language
{
    protected $tpl;
    protected $languages;
    protected $language;

    public function __construct()
    {
        $this->tpl = __DIR__ . '/lang_tpl.php';
        $this->run();
    }

    public function run()
    {
        $this->languages = App::$app->getProperty('languages');
        $this->language = App::$app->getProperty('language');
        echo $this->getHtmlLng();
    }

    public static function getLanguages(): array
    {
        return R::getAssoc('SELECT code, title, base, id FROM language ORDER BY base DESC');
    }

    public static function getLanguage($languages): array
    {
        $lang = App::$app->getProperty('lang');
        if ($lang && array_key_exists($lang, $languages)) {
            $key = $lang;
        } elseif (!$lang) {
            $key = key($languages);
        } else {
            $lang = h($lang);
            throw new \Exception("Not found language: {$lang}", 404);
        }
        
        $lang_inf = $languages[$key];
        $lang_inf['code'] = $key;
        return $lang_inf;
    }

    protected function getHtmlLng()
    {
        // debug($this->languages, 1);
        ob_start();
        require_once $this->tpl;
        return ob_get_clean();
    }
}
