<?php

namespace sw;

use RedBeanPHP\R;
use Valitron\Validator;

abstract class Model
{
    // to autocomplete the model with data from the user
    public array $attributes = [];
    public array $errors = [];
    public array $rules = [];
    public array $labels = [];

    public function __construct()
    {
        Db::getInstance();
    }

    // load from the user into the array only those attributes that we need
    // @data is the array POST
    public function load($data)
    {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function validate($data): bool
    {
        Validator::langDir(APP . '/languages/validator');
        $lang = App::$app->getProperty('language')['code'];
        $lang = $lang == 'ua' ? 'uk' : $lang;
        Validator::lang($lang);
        $validator = new Validator($data);
        $validator->rules($this->rules);
        $validator->labels($this->getLabels());
        if ($validator->validate()) {
            return true;
        } else {
            $this->errors = $validator->errors();
            return false;
        }
    }

    public function getErrors()
    {
        $errors = '<ul>';
        foreach ($this->errors as $errors_one_field) {
            foreach ($errors_one_field as $one_error) {
                $errors .= "<li>{$one_error}</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['errors'] = $errors;
    }

    public function getLabels(): array
    {
        $labels = [];
        foreach ($this->labels as $key => $val) {
            $labels[$key] = ___($val);
        }

        return $labels;
    }

    public function save($table): int|string
    {
        $tbl_obj = R::dispense($table);
        foreach ($this->attributes as $name => $value) {
            if ($value != '') {
                $tbl_obj->$name = $value;
            }
        }
        return R::store($tbl_obj);
    }
}
