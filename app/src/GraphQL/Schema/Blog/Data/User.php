<?php
namespace App\GraphQL\Schema\Blog\Data;

use GraphQL\Utils\Utils;

class User
{
    /** @var Integer */
    public $id;

    /** @var String */
    public $email;

    /** @var String */
    public $firstName;

    /** @var String */
    public $lastName;

    /** @var Boolean */
    public $hasPhoto;


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
            '1' => new User([
                'id' => '1',
                'email' => 'john@example.com',
                'firstName' => 'John',
                'lastName' => 'Doe'
            ]),
            '2' => new User([
                'id' => '2',
                'email' => 'jane@example.com',
                'firstName' => 'Jane',
                'lastName' => 'Doe'
            ]),
            '3' => new User([
                'id' => '3',
                'email' => 'john@example.com',
                'firstName' => 'John',
                'lastName' => 'Doe'
            ]),
        ];
    }

}
