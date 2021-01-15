<?php
namespace App\GraphQL\Schema\Blog\Data;

use GraphQL\Utils\Utils;

class Story
{
    public $id;

    public $authorId;

    public $title;

    public $body;

    public $isAnonymous = false;

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
            '1' => new Story([
                'id' => '1',
                'authorId' => '1',
                'body' => '<h1>GraphQL is awesome!</h1>'
            ]),
            '2' => new Story([
                'id' => '2',
                'authorId' => '1',
                'body' => '<a>Test this</a>'
            ]),
            '3' => new Story([
                'id' => '3',
                'authorId' => '3',
                'body' => "This\n<br>story\n<br>spans\n<br>newlines"
            ]),
        ];
    }
}
