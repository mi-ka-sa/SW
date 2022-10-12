<?php

namespace sw;

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
}
