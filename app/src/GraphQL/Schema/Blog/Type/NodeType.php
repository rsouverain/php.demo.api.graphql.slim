<?php
namespace App\GraphQL\Schema\Blog\Type;

use GraphQL\Type\Definition\InterfaceType;

use App\GraphQL\Schema\Blog\Data\Story\Story;
use App\GraphQL\Schema\Blog\Data\user\User;
use App\GraphQL\Schema\Blog\Data\Image\Image;
use App\GraphQL\Schema\Blog\TypeRegistry as Types;

class NodeType extends InterfaceType
{
    public function __construct()
    {
        $config = [
            'name' => 'Node',
            'fields' => [
                'id' => Types::id()
            ],
            'resolveType' => [$this, 'resolveNodeType']
        ];
        parent::__construct($config);
    }

    public function resolveNodeType($object)
    {
        if ($object instanceof User) {
            return Types::user();
        } else if ($object instanceof Image) {
            return Types::image();
        } else if ($object instanceof Story) {
            return Types::story();
        }
    }
}
