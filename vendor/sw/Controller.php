<?php

namespace sw;

abstract class Controller
{
    // array for data from Model and transfer to View
    public array $data = [];

    public array $meta = [];
    public false|string $layout = '';
    public string $view = '';
    public object $model;


    public function __construct(public $route = [])
    {
        
    }

    public function getModel()
    {
        $model = 'app\models\\' . $this->route['admin_prefix'] . $this->route['controller'];
        if (class_exists($model)) {
            $this->model = new $model();
        }
    }

    public function getView()
    {
        $this->view = $this->view ?: $this->route['action'];
        (new View ($this->route, $this->layout, $this->view, $this->meta))->render($this->data);
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function setMeta($title = '', $desc = '', $keywords = '') {
        $this->meta = [
            'title' => $title, 
            'desc' => $desc, 
            'keywords' => $keywords
        ];
    }
}
