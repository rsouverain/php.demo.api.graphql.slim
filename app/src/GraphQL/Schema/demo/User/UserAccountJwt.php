<?php

namespace App\GraphQL\Schema\demo\User;

use App\Boilerplate\GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Schema\demo\TypeRegistry;
use GraphQL\Type\Definition\ResolveInfo;

class UserAccountJwt extends ObjectType
{
    public function __construct()
    {
        $types = TypeRegistry::getInstance();

        $config = [
            'name' => 'UserAccountJwt',
            'description' => 'A JSON Web Token for an UserAccount',
            'interfaces' => [
            ],
            'fields' => [
                'user' => [
                    'type' => $types::nonNull($types->UserAccount()),
                    'description' => 'The corresponding user',
                ],
                'token' => [
                    'type' => $types::nonNull($types::string()),
                    'description' => 'A JWT for the user',
                ],
                'expire' => [
                    'type' => $types::nonNull($types->DateTime()),
                    'description' => 'Expiration date for this JWT (faked in this demo)',
                ],
            ],
        ];
        parent::__construct($config);
    }

}
