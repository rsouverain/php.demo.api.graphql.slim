<?php
namespace App\Blog\Data;

use App\Blog\Data\Comment\Comment;
use App\Blog\Data\Story\Story;
use App\Blog\Data\User\User;

/**
 * This is just a simple in-memory data holder for the sake of example.
 * Data layer for real app may use Doctrine or query the database directly (e.g. in CQRS style)
 */
class DataSource
{
    /** @var DataSource|null */
    protected static $mainInstance;

    /** @var array */
    public static $users;

    /** @var array */
    public static $images;

    /** @var array */
    public static $stories;

    /** @var array */
    public static $comments;


    public static $storyComments = [];
    public static $commentReplies = [];
    public static $storyMentions = [];


    /**
     * Datasource constructor.
     */
    public function __construct()
    {
        self::init();
    }


    /**
     * @return DataSource|null
     */
    public static function getInstance () {
        if (!self::$mainInstance) {
            self::$mainInstance = new self();
        }
        return self::$mainInstance;
    }

    public static function init()
    {
        self::$users = User::init();

        self::$stories = Story::init();

        self::$storyLikes = [
            '1' => ['1', '2', '3'],
            '2' => [],
            '3' => ['1']
        ];

        self::$comments = Comment::init();

        self::$storyComments = [
            '1' => ['100', '200', '300'],
            '2' => ['400', '500']
        ];

        self::$commentReplies = [
            '100' => ['110', '120', '130'],
            '110' => ['111', '112', '113', '114', '115', '116', '117'],
        ];

        self::$storyMentions = [
            '1' => [
                self::$users['2']
            ],
            '2' => [
                self::$stories['1'],
                self::$users['3']
            ]
        ];
    }


















}
