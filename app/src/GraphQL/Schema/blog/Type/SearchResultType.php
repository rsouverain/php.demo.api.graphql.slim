<?php
namespace App\GraphQL\Schema\blog\Type;

use GraphQL\Type\Definition\UnionType;

use App\GraphQL\Schemma\blog\Data\Story;
use App\GraphQL\Schemma\blog\Data\User;
use App\GraphQL\Schemma\blog\TypeRepository as Types;

class SearchResultType extends UnionType
{
    public function __construct()
    {
        $config = [
            'name' => 'SearchResultType',
            'types' => function() {
                return [
                    Types::story(),
                    Types::user()
                ];
            },
            'resolveType' => function($value) {
                if ($value instanceof Story) {
                    return Types::story();
                } else if ($value instanceof User) {
                    return Types::user();
                }
            }
        ];
        parent::__construct($config);
    }
}