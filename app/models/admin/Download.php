<?php

namespace app\models\admin;

use app\models\AppModel;
use RedBeanPHP\R;

class Download extends AppModel
{
    public function getDownloadsFile($lang, $start, $perpage): array
    {
        return R::getAll("SELECT d.*, dd.* FROM download d
                        JOIN download_desc dd
                        ON d.id = dd.download_id
                        WHERE dd.language_id = ?
                        LIMIT $start, $perpage", 
                        [$lang]);
    }
}

