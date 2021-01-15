<?php

namespace App\GraphQL\Schema\demo\User;

use App\Boilerplate\GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Schema\demo\TypeRegistry;
use GraphQL\Type\Definition\ResolveInfo;

class UserAccount extends ObjectType
{
    public function __construct()
    {
        $types = TypeRegistry::getInstance();

        $config = [
            'name' => 'UserAccount',
            'description' => 'Our blog authors',
            'interfaces' => [
                $types->DataNodeInterface(),
            ],
            'fields' => function() use ($types) {
                return [
                    'id' => [
                        'type' => $types::id(),
                        'description' => 'User\'s unique identifier',
                    ],
                    'email' => [
                        'type' => $types->Email(),
                        'description' => 'User\'s email address',
                    ],
                    'firstName' => [
                        'type' => $types::string(),
                    ],
                    'lastName' => [
                        'type' => $types::string(),
                    ],
                ];
            },
        ];
        parent::__construct($config);
    }

}
