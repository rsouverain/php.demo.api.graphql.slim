<?php


namespace App\GraphQL\Schema\Blog\Domain\Comment;


use App\GraphQL\Schema\Blog\Data\BlogDataSource;

class CommentController extends BlogDataSource
{

    /**
     * @param integer $commentId
     * @return Comment|null
     */
    public static function findComment($commentId)
    {
        return isset(self::$comments[$id]) ? self::$comments[$id] : null;
    }


    /**
     * @param integer $commentId
     * @param integer $limit
     * @param integer $after
     * @return array
     */
    public static function findReplies($commentId, $limit = 5, $afterId = null)
    {
        $commentReplies = isset(self::$commentReplies[$commentId]) ? self::$commentReplies[$commentId] : [];

        $start = isset($after) ? (int) array_search($afterId, $commentReplies) + 1: 0;
        $commentReplies = array_slice($commentReplies, $start, $limit);

        return array_map(
            function($replyId) {
                return self::$comments[$replyId];
            },
            $commentReplies
        );
    }


    /**
     * @param integer $commentId
     * @return integer
     */
    public static function countReplies($commentId)
    {
        return isset(self::$commentReplies[$commentId]) ? count(self::$commentReplies[$commentId]) : 0;
    }

}