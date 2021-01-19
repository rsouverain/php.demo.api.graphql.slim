<?php
namespace App\GraphQL\Schema\Blog\Type;

use App\GraphQL\Schema\Blog\Domain\Services\User\UserService;
use App\GraphQL\Schema\Blog\Domain\Story\StoryController;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;


use App\GraphQL\Schema\Blog\TypeRegistry as Types;

class QueryType extends ObjectType
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();

        $config = [
            'name' => 'Query',
            'fields' => [
                'user' => [
                    'type' => Types::user(),
                    'description' => 'Returns User by id (in range of 1-5)',
                    'args' => [
                        'id' => Types::nonNull(Types::id())
                    ]
                ],
                'viewer' => [
                    'type' => Types::user(),
                    'description' => 'Represents currently logged-in user (for the sake of example - simply returns user with id == 1)'
                ],
                'stories' => [
                    'type' => Types::listOf(Types::story()),
                    'description' => 'Returns subset of stories posted for this Blog',
                    'args' => [
                        'after' => [
                            'type' => Types::id(),
                            'description' => 'Fetch stories listed after the story with this ID'
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of stories to be returned',
                            'defaultValue' => 10
                        ]
                    ]
                ],
                'lastStoryPosted' => [
                    'type' => Types::story(),
                    'description' => 'Returns last story posted for this Blog'
                ],
                'deprecatedField' => [
                    'type' => Types::string(),
                    'deprecationReason' => 'This field is deprecated!'
                ],
                'fieldWithException' => [
                    'type' => Types::string(),
                    'resolve' => function() {
                        throw new \Exception("Exception message thrown in field resolver");
                    }
                ],
            ],
            'resolveField' => function($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];
        parent::__construct($config);
    }

    public function user($rootValue, $args)
    {
        return $this->userService->findUser($args['id']);
    }

    public function viewer($rootValue, $args)
    {
        return UserService::findUser("1");
        /*        return $context->viewer;*/
    }

    public function stories($rootValue, $args)
    {
        $args += ['after' => null];
        return StoryController::findStories($args['limit'], $args['after']);
    }

    public function lastStoryPosted()
    {
        return StoryController::findLatestStory();
    }


    public function deprecatedField()
    {
        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
    }

}
