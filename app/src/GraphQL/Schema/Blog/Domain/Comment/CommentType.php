<?php
namespace App\GraphQL\Schema\Blog\Domain\Comment;

use App\GraphQL\Schema\Blog\Domain\User\UserController;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

use App\GraphQL\Schema\Blog\Data\Comment;
use App\GraphQL\Schema\Blog\TypeRegistry as Types;


class CommentType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Comment',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'author' => Types::user(),
                    'parent' => Types::comment(),
                    'isAnonymous' => Types::boolean(),
                    'replies' => [
                        'type' => Types::listOf(Types::comment()),
                        'args' => [
                            'after' => Types::int(),
                            'limit' => [
                                'type' => Types::int(),
                                'defaultValue' => 5
                            ]
                        ]
                    ],
                    'totalReplyCount' => Types::int(),

                    Types::htmlField('body')
                ];
            },
            'resolveField' => function($comment, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($comment, $args, $context, $info);
                } else {
                    return $comment->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    public function resolveAuthor(Comment $comment)
    {
        if ($comment->isAnonymous) {
            return null;
        }
        return UserController::findUser($comment->authorId);
    }

    public function resolveParent(Comment $comment)
    {
        if ($comment->parentId) {
            return CommentController::findComment($comment->parentId);
        }
        return null;
    }

    public function resolveReplies(Comment $comment, $args)
    {
        $args += ['after' => null];
        return CommentController::findReplies($comment->id, $args['limit'], $args['after']);
    }

    public function resolveTotalReplyCount(Comment $comment)
    {
        return CommentController::countReplies($comment->id);
    }
}
