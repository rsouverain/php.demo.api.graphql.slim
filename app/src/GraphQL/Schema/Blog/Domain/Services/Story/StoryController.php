<?php


namespace App\GraphQL\Schema\Blog\Domain\Story;


use App\GraphQL\Schema\Blog\Data\BlogDataSource;
use App\GraphQL\Schema\Blog\Data\Story;

class StoryController extends BlogDataSource
{
    /**
     * @param integer $id
     * @return Story|null
     */
    public static function findStory($id)
    {
        return isset(self::$stories[$id]) ? self::$stories[$id] : null;
    }

    /**
     * @param integer $authorId
     * @return Story|null
     */
    public static function findLastStoryFor($authorId)
    {
        $storiesFound = array_filter(self::$stories, function(Story $story) use ($authorId) {
            return $story->authorId == $authorId;
        });
        return !empty($storiesFound) ? $storiesFound[count($storiesFound) - 1] : null;
    }

    /**
     * @param integer $storyId
     * @param integer $limit
     * @return array
     */
    public static function findLikes($storyId, $limit)
    {
        $likes = isset(self::$storyLikes[$storyId]) ? self::$storyLikes[$storyId] : [];
        $result = array_map(
            function($userId) {
                return self::$users[$userId];
            },
            $likes
        );
        return array_slice($result, 0, $limit);
    }

    /**
     * @param integer $storyId
     * @param integer $userId
     * @return boolean
     */
    public static function isLikedBy($storyId, $userId)
    {
        $subscribers = isset(self::$storyLikes[$storyId]) ? self::$storyLikes[$storyId] : [];
        return in_array($userId, $subscribers);
    }

    /**
     * @return Story
     */
    public static function findLatestStory()
    {
        return array_pop(self::$stories);
    }


    /**
     * @param integer $limit
     * @param boolean $afterId
     * @return array
     */
    public static function findStories($limit, $afterId = null)
    {
        $start = $afterId ? (int) array_search($afterId, array_keys(self::$stories)) + 1 : 0;
        return array_slice(array_values(self::$stories), $start, $limit);
    }

    /**
     * @param integer $storyId
     * @return integer
     */
    public static function countComments($storyId)
    {
        return isset(self::$storyComments[$storyId]) ? count(self::$storyComments[$storyId]) : 0;
    }


    /**
     * @param integer $storyId
     * @return array
     */
    public static function findStoryMentions($storyId)
    {
        return isset(self::$storyMentions[$storyId]) ? self::$storyMentions[$storyId] :[];
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


}