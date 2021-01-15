<?php


namespace App\GraphQL\Schema\blog\Data\comment;


use App\Blog\Data\Comment\Comment;
use App\Blog\Data\DataSource;

class CommentController extends DataSource
{
    /**
     * @param integer $id
     * @return Comment|null
     */
    public static function findComment($id)
    {
        return isset(self::$comments[$id]) ? self::$comments[$id] : null;
    }


    /**
     * @param integer $storyId
     * @param integer $limit
     * @param integer $afterId
     * @return array
     */
    public static function findComments($storyId, $limit = 5, $afterId = null)
    {
        $storyComments = isset(self::$storyComments[$storyId]) ? self::$storyComments[$storyId] : [];

        $start = isset($after) ? (int) array_search($afterId, $storyComments) + 1 : 0;
        $storyComments = array_slice($storyComments, $start, $limit);

        return array_map(
            function($commentId) {
                return self::$comments[$commentId];
            },
            $storyComments
        );
    }

    /**
     * @param integer $commentId
     * @param integer $limit
     * @param integer $afterId
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