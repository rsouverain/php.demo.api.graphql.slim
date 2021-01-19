<?php

namespace App\Boilerplate\Data;

abstract class DefaultRepositoryAbstract implements DefaultRepositoryInterface
{
    private static $instance;
    public static function getInstance ()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}
