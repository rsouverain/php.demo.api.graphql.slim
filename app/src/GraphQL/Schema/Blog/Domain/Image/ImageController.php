<?php


namespace App\GraphQL\Schema\Blog\Domain\Image;


use App\GraphQL\Schema\Blog\Data\BlogDataSource;
use App\GraphQL\Schema\Blog\Data\Image;

class ImageController extends BlogDataSource
{
    /**
     * @param integer $userId
     * @param integer $size
     * @return Image
     */
    public static function getUserPhoto($userId, $size)
    {
        return new Image([
            'id' => $userId,
            'type' => Image::TYPE_USERPIC,
            'size' => $size,
            'width' => rand(100, 200),
            'height' => rand(100, 200)
        ]);
    }
}