<?php
namespace App\GraphQL\Schema\Blog\Domain\Repository\User;

use GraphQL\Utils\Utils;

class UserData
{
    /** @var Integer */
    public $id;

    /** @var String */
    public $login;

    /** @var String */
    public $email;

    /** @var String */
    public $password;

    /** @var Array */
    public $aclProfiles;

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

}
