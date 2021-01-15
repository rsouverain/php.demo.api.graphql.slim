<?php


namespace App\GraphQL\Schema\blog\Data\image;


use App\Blog\Data\DataSource;
use App\Blog\Data\Image\Image;

class ImageController extends DataSource
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