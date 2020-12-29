<?php

namespace App\GraphQL\Schema\blog\Type\Enum;

use GraphQL\Type\Definition\EnumType;

use App\GraphQL\Schema\blog\Data\Image;

class ImageSizeEnumType extends EnumType
{
    public function __construct()
    {
        $config = [
            // Note: 'name' option is not needed in this form - it will be inferred from className
            'values' => [
                'ICON' => Image::SIZE_ICON,
                'SMALL' => Image::SIZE_SMALL,
                'MEDIUM' => Image::SIZE_MEDIUM,
                'ORIGINAL' => Image::SIZE_ORIGINAL
            ]
        ];

        parent::__construct($config);
    }
}