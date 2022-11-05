<?php

namespace app\controllers\admin;

use sw\App;
use RedBeanPHP\R;
use sw\Pagination;

class DownloadController extends AppController
{
    public function indexAction()
    {
        $lang = App::$app->getProperty('language')['id'];
        $page = get('page');
        $perpage = 50;
        $total = R::count('download');
        $pagination = new Pagination($page, $perpage, $total);
        $start_pos = $pagination->getStart();

        $download_files = $this->model->getDownloadsFile($lang, $start_pos, $perpage);

        $title = 'Digital products (files)';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'download_files', 'pagination', 'total'));
    }
}
