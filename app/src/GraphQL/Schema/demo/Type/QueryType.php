<?php
namespace App\GraphQL\Schema\demo\Type;

use App\Boilerplate\GraphQL\Exception\AccessDeniedException;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use App\GraphQL\Schema\Blog\TypeRegistry;


class QueryType extends ObjectType
{
    public function __construct()
    {
        $typeRegistry = TypeRegistry::getInstance();

        $config = [
            'name' => 'Query',
            'fields' => [
                'deprecatedField' => [
                    'type' => $typeRegistry::string(),
                    'deprecationReason' => 'This field is deprecated!'
                ],
                'fieldWithException' => [
                    'type' => $typeRegistry::string(),
                    'resolve' => function() {
                        throw new Error("Exception message thrown in field resolver");
                    }
                ],
                'hello' => $typeRegistry::nonNull(
                    $typeRegistry::listOf(
                        $typeRegistry::nonNull(
                            $typeRegistry::string()
                        )
                    )
                ),
            ],
            'resolveField' => function($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }


    public function hello($rootValue, $args, $context, $info)
    {
        return ["World", "!"];
    }

    public function deprecatedField()
    {
        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
    }
}
