<?php

namespace App\Boilerplate\GraphQL\Type;

use App\Boilerplate\GraphQL\Exception\GenericGraphQlException;

trait SingletonTypeTrait
{
    private static $_instance;
    public static function getType () {
        if (!self::$_instance) {
            throw new GenericGraphQlException('Not Instantiated Yet.');
        }
        return self::$_instance;
    }
}
