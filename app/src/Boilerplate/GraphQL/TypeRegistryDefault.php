<?php

namespace App\Boilerplate\GraphQL;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\IntType;
use GraphQL\Type\Definition\BooleanType;
use GraphQL\Type\Definition\FloatType;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\StringType;

/**
 * Class TypeRegistryDefault
 * @package App\Boilerplate\GraphQL
 */
class TypeRegistryDefault extends TypeRegistryAbstract
{

    private static $mainInstance;
    public static function getInstance () {
        if (!self::$mainInstance) {
            self::$mainInstance = new static();
        }
        return self::$mainInstance;
    }

    // Let's add internal types as well for consistent experience

    /**
     * @return BooleanType|ScalarType
     */
    public static function boolean()
    {
        return Type::boolean();
    }

    /**
     * @return FloatType|ScalarType
     */
    public static function float()
    {
        return Type::float();
    }

    /**
     * @return IDType|ScalarType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @return IntType|ScalarType
     */
    public static function int()
    {
        return Type::int();
    }

    /**
     * @return StringType|ScalarType
     */
    public static function string()
    {
        return Type::string();
    }

    /**
     * @param Type|callable $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * @param Type|callable $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }
}
