<?php
namespace App\GraphQL\Schema\Blog\Data;


use GraphQL\Utils\Utils;

class Comment
{
    public $id;

    public $authorId;

    public $storyId;

    public $parentId;

    public $body;

    public $isAnonymous;

    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }

    /**
     * @return array
     */
    public static function init()
    {
        return [
            // thread #1:
            '100' => new Comment([
                'id' => '100',
                'authorId' => '3',
                'storyId' => '1',
                'body' => 'Likes']),
            '
            110' => new Comment([

                'id' =>'110',
                'authorId' =>'2',
                'storyId' => '1',
                'body' => 'Reply <b>#1</b>',
                'parentId' =>'100']
            ),
            '111' => new Comment([
                'id' => '111',
                'authorId' => '1',
                'storyId' => '1',
                'body' => 'Reply #1-1',
                'parentId' => '110'
            ]),
            '112' => new Comment([
                'id' => '112',
                'authorId' => '3',
                'storyId' => '1',
                'body' => 'Reply #1-2',
                'parentId' => '110'
            ]),
            '113' => new Comment([
                'id' => '113',
                'authorId' => '2',
                'storyId' => '1',
                'body' => 'Reply #1-3',
                'parentId' => '110'
            ]),
            '114' => new Comment([
                'id' => '114',
                'authorId' => '1',
                'storyId' => '1',
                'body' => 'Reply #1-4',
                'parentId' => '110'
            ]),
            '115' => new Comment([
                'id' => '115',
                'authorId' => '3',
                'storyId' => '1',
                'body' => 'Reply #1-5',
                'parentId' => '110'
            ]),
            '116' => new Comment([
                'id' => '116',
                'authorId' => '1',
                'storyId' => '1',
                'body' => 'Reply #1-6',
                'parentId' => '110'
            ]),
            '117' => new Comment([
                'id' => '117',
                'authorId' => '2',
                'storyId' => '1',
                'body' => 'Reply #1-7',
                'parentId' => '110'
            ]),
            '120' => new Comment([
                'id' => '120',
                'authorId' => '3',
                'storyId' => '1',
                'body' => 'Reply #2',
                'parentId' => '100'
            ])
            ,
            '130' => new Comment([
                'id' => '130',
                'authorId' => '3',
                'storyId' => '1',
                'body' => 'Reply #3',
                'parentId' => '100'
            ])
            ,
            '200' => new Comment([
                'id' => '200',
                'authorId' => '2',
                'storyId' => '1',
                'body' => 'Me2'
            ]),
            '300' => new Comment([
                'id' => '300',
                'authorId' => '3',
                'storyId' => '1',
                'body' => 'U2'
            ]),

            # thread #2:
            '400' => new Comment([
                'id' => '400',
                'authorId' => '2',
                'storyId' => '2',
                'body' => 'Me too'
            ]),
            '500' => new Comment([
                'id' => '500',
                'authorId' => '2',
                'storyId' => '2',
                'body' => 'Nice!'
            ]),
        ];
    }
}
