<?php


namespace App\GraphQL\Schema\blog\Data\user;


use App\Blog\Data\DataSource;


class UserController extends DataSource
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