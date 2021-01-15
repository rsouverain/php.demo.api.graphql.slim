<?php

namespace App\GraphQL\Schema\demo\User;

use App\Boilerplate\GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Schema\demo\TypeRegistry;
use GraphQL\Type\Definition\ResolveInfo;

class UserNamespaceQuery extends ObjectType
{
    public function __construct()
    {
        $types = TypeRegistry::getInstance();

        $config = [
            'name' => 'UserNamespaceQuery',
            'description' => 'Interact with the APIs for user related purposes',
            'interfaces' => [],
            'fields' => [

                'getJWT' => [
                    'type' => $types::string(),
                    'description' => 'Request a valid JWT token using the user\'s credentials',
                    'args' => [
                        [
                            'name' => 'username',
                            'type' => $types::string(),
                            'description' => 'User login handle',
                            'defaultValue' => null,
                        ],
                        [
                            'name' => 'password',
                            'type' => $types::string(),
                            'description' => 'User password (plain text)',
                            'defaultValue' => null,
                        ],
                    ],
                    'resolve' => function ($rootValue, $args, $context, ResolveInfo $info) {
                        return "notimplementedyet";
                    },
                ],

            ],
        ];
        parent::__construct($config);
    }


}
