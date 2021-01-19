<?php
namespace App\GraphQL\Schema\Blog\Domain\Story;

use App\GraphQL\Schema\Blog\Domain\Comment\CommentController;
use App\GraphQL\Schema\Blog\Domain\User\UserService;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use App\GraphQL\Schema\Blog\Data\Story;

use App\GraphQL\Schema\Blog\TypeRegistry as Types;

class StoryType extends ObjectType
{
    const EDIT = 'EDIT';
    const DELETE = 'DELETE';
    const LIKE = 'LIKE';
    const UNLIKE = 'UNLIKE';
    const REPLY = 'REPLY';

    public function __construct()
    {
        $config = [
            'name' => 'Story',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'author' => Types::user(),
                    'mentions' => Types::listOf(Types::mention()),
                    'totalCommentCount' => Types::int(),
                    'comments' => [
                        'type' => Types::listOf(Types::comment()),
                        'args' => [
                            'after' => [
                                'type' => Types::id(),
                                'description' => 'Load all comments listed after given comment ID'
                            ],
                            'limit' => [
                                'type' => Types::int(),
                                'defaultValue' => 5
                            ]
                        ]
                    ],
                    'likes' => [
                        'type' => Types::listOf(Types::user()),
                        'args' => [
                            'limit' => [
                                'type' => Types::int(),
                                'description' => 'Limit the number of recent likes returned',
                                'defaultValue' => 5
                            ]
                        ]
                    ],
                    'likedBy' => [
                        'type' => Types::listOf(Types::user()),
                    ],
                    'affordances' => Types::listOf(new EnumType([
                        'name' => 'StoryAffordancesEnum',
                        'values' => [
                            self::EDIT,
                            self::DELETE,
                            self::LIKE,
                            self::UNLIKE,
                            self::REPLY
                        ]
                    ])),
                    'hasViewerLiked' => Types::boolean(),

                    Types::htmlField('body'),
                ];
            },
            'interfaces' => [
                Types::node()
            ],
            'resolveField' => function($story, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($story, $args, $context, $info);
                } else {
                    return $story->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    public function resolveAuthor(Story $story)
    {
        return UserService::findUser($story->authorId);
    }

    public function resolveAffordances(Story $story, $args, $context)
    {
        $isViewer = $context->viewer === UserService::findUser($story->authorId);
        $isLiked = StoryController::isLikedBy($story->id, $context->viewer->id);

        if ($isViewer) {
            $affordances[] = self::EDIT;
            $affordances[] = self::DELETE;
        }
        if ($isLiked) {
            $affordances[] = self::UNLIKE;
        } else {
            $affordances[] = self::LIKE;
        }
        return $affordances;
    }

    public function resolveHasViewerLiked(Story $story, $args, $context, $info)
    {
        return StoryController::isLikedBy($story->id, $context->viewer->id);
    }

    public function resolveTotalCommentCount(Story $story)
    {
        return StoryController::countComments($story->id);
    }

    public function resolveComments(Story $story, $args)
    {
        $args += ['after' => null];
/*        var_dump($story->id);
        var_dump($args);*/

        return StoryController::findComments($story->id, $args['limit'], $args['after']);
    }
    
    public function resolveMentions(Story $story, $args, $context){
        return StoryController::findStoryMentions($story->id);
    }

    public function resolveLikedBy(Story $story, $args, $context){
        return StoryController::findLikes($story->id,10);
    }

    public function resolveLikes(Story $story, $args, $context){
        return StoryController::findLikes($story->id,10);
    }
}
