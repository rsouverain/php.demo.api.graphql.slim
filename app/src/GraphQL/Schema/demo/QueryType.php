<?php

namespace App\GraphQL\Schema\demo;

use App\Boilerplate\GraphQL\Exception\AccessDeniedException;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;



class QueryType extends ObjectType
{
    public function __construct()
    {
        $types = TypeRegistry::getInstance();

        $config = [
            'name' => 'Query',
            'fields' => [
                'deprecatedField' => [
                    'type' => $types::string(),
                    'deprecationReason' => 'This field is deprecated!',
                    'resolve' => function() {
                        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
                    }
                ],
                'fieldWithException' => [
                    'type' => $types::string(),
                    'resolve' => function() {
                        throw new Error("Exception message thrown in field resolver");
                    }
                ],
                'user' => [
                    'type' => $types->UserNamespaceQuery(),
                    'description' => 'Interact with the APIs for user related purposes',
                    'resolve' => function () { return [];  }
                ],
                'hello' => [
                    'type' => $types::nonNull(
                        $types::listOf(
                            $types::nonNull(
                                $types::string()
                            )
                        )
                    ),
                    'resolve' => function ($rootValue, $args, $context, $info) {
                        return ["World", "!"];
                    },
                ],
            ],
//            'resolveField' => function($rootValue, $args, $context, ResolveInfo $info) {
//                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
//            }
        ];
        parent::__construct($config);
    }


}
