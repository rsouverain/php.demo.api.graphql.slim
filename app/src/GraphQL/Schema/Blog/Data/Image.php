<?php
namespace App\GraphQL\Schema\Blog\Data;

use GraphQL\Utils\Utils;

class Image
{
    const TYPE_USERPIC = 'userpic';

    const SIZE_ICON = 'icon';
    const SIZE_SMALL = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_ORIGINAL = 'original';

    /** @var Integer */
    public $id;

    /** @var String */
    public $type;

    /** @var Integer */
    public $size;

    /** @var Integer */
    public $width;

    /** @var Integer */
    public $height;

    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }

}
