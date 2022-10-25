<?php

namespace app\controllers\admin;

class CategoryController extends AppController
{
    public function indexAction()
    {
        $title = 'Categories';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title'));
    }

    public function deleteAction()
    {
        $id_category = get('id');
        $this->model->deleteCategory($id_category);
        redirect();
    }

    public function addAction()
    {
        if (!empty($_POST)) {
            if ($this->model->categoryValidate()) {
                if ($this->model->saveCategory()) {
                    $_SESSION['success'] = 'New category added';
                } else {
                    $_SESSION['errors'] = 'Error adding new category';
                }
            }
            redirect();
        }

        $title = 'New category';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title'));
    }
}
