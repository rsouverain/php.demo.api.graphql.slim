<?php

namespace App\Boilerplate\GraphQL\Type\Definition;

use App\Boilerplate\GraphQL\Type\SingletonTypeTrait;

class InterfaceType extends \GraphQL\Type\Definition\InterfaceType
{
    use SingletonTypeTrait;

    public function __construct(array $config)
    {
        self::$_instance = $this;
        parent::__construct($config);
    }

}
