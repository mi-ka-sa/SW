<?php

namespace sw;

trait TSingleton
{
    // "self|null" or "?self" - variable can store either an instance of the class or null
    private static self|null $instance = null;

    private function __construct(){}

    // method to get an instance of a class without using the operator "new" 
    public static function getInstance(): static
    {
        return static::$instance ?? static::$instance = new static();
    }
}
