<?php

namespace App\GraphQL\Schema\Blog\Domain\Services\User;

use App\GraphQL\Schema\Blog\Domain\Services\User\UserService;
use GraphQL\Executor\Promise\Adapter\SyncPromise;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use App\GraphQL\Schema\Blog\Domain\Repository\User\UserData;
use App\GraphQL\Schema\Blog\Domain\Image\ImageController;
use App\GraphQL\Schema\Blog\Domain\Story\StoryController;
use App\GraphQL\Schema\Blog\Repository\User\UserRepository;

use App\GraphQL\Schema\Blog\TypeRegistry as Types;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

class UserType extends ObjectType
{

    /**
     * @var UserService
     */
    private $userService;


    public function __construct()
    {
        $this->userService = new UserService();


        $config = [
            'name' => 'User',
            'description' => 'Our Blog authors',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'email' => Types::email(),
                    'photo' => [
                        'type' => Types::image(),
                        'description' => 'User photo URL',
                        'args' => [
                            'size' => Types::nonNull(Types::imageSizeEnum()),
                        ]
                    ],
                    'firstName' => [
                        'type' => Types::string(),
                    ],
                    'lastName' => [
                        'type' => Types::string(),
                    ],
                    'lastStoryPosted' => Types::story(),
                    'fieldWithError' => [
                        'type' => Types::string(),
                        'resolve' => function() {
                            throw new \Exception("This is error field");
                        }
                    ]
                ];
            },
            'interfaces' => [
                Types::node()
            ],
            'resolveField' => function($user, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($user, $args, $context, $info);
                } else {
                    return $user->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    public function resolvePhoto(UserData $user, $args)
    {
        return ImageController::getUserPhoto($user->id, $args['size']);
    }

    public function resolveLastStoryPosted(UserData $user)
    {
        return StoryController::findLastStoryFor($user->id);
    }
}
