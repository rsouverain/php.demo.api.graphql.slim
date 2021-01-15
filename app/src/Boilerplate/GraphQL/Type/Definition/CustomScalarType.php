<?php

namespace App\Boilerplate\GraphQL\Type\Definition;

use App\Boilerplate\GraphQL\Type\SingletonTypeTrait;

class CustomScalarType extends \GraphQL\Type\Definition\CustomScalarType
{
    use SingletonTypeTrait;

    public function __construct(array $config = [])
    {
        self::$_instance = $this;
        parent::__construct($config);
    }


}
