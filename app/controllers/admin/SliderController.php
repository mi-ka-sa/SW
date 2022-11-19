<?php

namespace app\controllers\admin;

/** @property Slider model*/
class SliderController extends AppController
{
    public function indexAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->updateSlider();
            $_SESSION['success'] = 'Slider was update';
            redirect();
        }

        $gallery = $this->model->getSlides();

        $title = 'Slider';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'gallery'));
    }
}
