<?php


namespace App\GraphQL\Schema\Blog\Domain\User;

use App\GraphQL\Schema\Blog\Data\BlogDataSource;

class UserController extends BlogDataSource
{

    /**
     * @param integer $id
     * @return User|null
     */
    public static function findUser($id)
    {
        return isset(self::$users[$id]) ? self::$users[$id] : null;
    }

}