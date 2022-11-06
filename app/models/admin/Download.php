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

    public function downloadValidate(): bool
    {
        $errors = '';
        foreach ($_POST['download_desc'] as $lang_id => $item) {
            $item['name'] = trim($item['name']);
            if (empty($item['name'])) {
                $errors .= "The file name is not indicated {$lang_id}<br>";
            }
        }

        if (empty($_FILES) || $_FILES['file']['error']) {
            $errors .= "File loading error<br>";
        } else {
            $extensions_array = ['jpg', 'jpeg', 'png', 'zip', 'pdf', 'txt'];
            $parts = explode('.', $_FILES['file']['name']);
            $extensions_file = end($parts);

            if (!in_array($extensions_file, $extensions_array)) {
            $errors .= "The file with '.{$extensions_file}' extension cannot be upload<br>";
        }
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            return false;
        }

        return true;
    }

    public function uploadFile(): array|false
    {
        $file_name = $_FILES['file']['name'] . uniqid();
        $path = WWW . '/downloads/' . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
            return [
                'original_name' => $_FILES['file']['name'],
                'filename' => $file_name,
            ];
        }

        return false;
    }

    public function saveDownload($data_arr): bool
    {
        R::begin();
        try {
            $download = R::dispense('download');
            $download->filename = $data_arr['filename'];
            $download->original_name = $data_arr['original_name'];
            $download_id = R::store($download);

            foreach ($_POST['download_desc'] as $lang_id => $item) {
                R::exec("INSERT INTO download_desc (download_id, language_id, name)
                        VALUES (?,?,?)", 
                        [
                            $download_id, 
                            $lang_id, 
                            $item['name']
                        ]);
            }

            R::commit();
            return true;
        } catch (\Throwable $th) {
            R::rollback();
            return false;
        }
    }
}

