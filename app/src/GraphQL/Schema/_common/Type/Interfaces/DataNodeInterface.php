<?php

namespace App\GraphQL\Schema\_common\Type\Interfaces;

use App\Boilerplate\GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;


/**
 * @see http://webonyx.github.io/graphql-php/type-system/interfaces/
 */

class DataNodeInterface extends InterfaceType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'DataNodeInterface',
            'description' => 'An uniquely identifiable data node',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::id()),
                    'description' => 'Identifier',
                ],
            ],
        ]);
    }
}

