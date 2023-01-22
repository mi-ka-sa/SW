<?php

namespace app\controllers\admin;

use Exception;
use sw\App;

/** @property Category $model */
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

    public function editAction()
    {
        $id = get('id');
        if (!empty($_POST)) {
            if (!$this->model->categoryValidate()) {
                redirect();
            }

            if ($this->model->updateCategory($id)) {
                $_SESSION['success'] = 'Category has been updated';
            } else {
                $_SESSION['errors'] = 'Category update error';
            }
            redirect();
        }

        $category = $this->model->getCategotyInfo($id);

        if (!$category) {
            throw new Exception('Not found category', 404);
        }

        $lang = App::$app->getProperty('language')['id'];
        App::$app->setProperty('parent_id', $category[$lang]['parent_id']);
        $title = 'Edit category';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'category'));
    }
}
