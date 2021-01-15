<?php

namespace App\Boilerplate\GraphQL\Type\Definition;

use App\Boilerplate\GraphQL\Type\SingletonTypeTrait;

abstract class ScalarType extends \GraphQL\Type\Definition\ScalarType
{
    use SingletonTypeTrait;

    public function __construct(array $config = [])
    {
        self::$_instance = $this;
        parent::__construct($config);
    }

}
