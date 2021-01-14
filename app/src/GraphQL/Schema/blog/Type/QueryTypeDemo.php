<?php
namespace App\GraphQL\Schema\blog\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use App\GraphQL\Schema\blog\TypeRegistry;


class QueryTypeDemo extends ObjectType
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
                        throw new \Exception("Exception message thrown in field resolver");
                    }
                ],
                'hello' => $typeRegistry::string()
            ],
            'resolveField' => function($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }


    public function hello()
    {
        return 'World !';
    }

    public function deprecatedField()
    {
        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
    }
}
