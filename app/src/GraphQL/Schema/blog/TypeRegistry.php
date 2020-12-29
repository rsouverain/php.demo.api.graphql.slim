<?php

namespace App\GraphQL\Schema\blog;

use Exception;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

use App\GraphQL\Boilerplate\TypeRegistryDefault;

use App\GraphQL\Schema\_common\Type\Scalar\UrlType;
use App\GraphQL\Schema\_common\Type\Scalar\EmailType;
use App\GraphQL\Schema\blog\Type\Enum\ContentFormatEnum;
use App\GraphQL\Schema\blog\Type\Enum\ImageSizeEnumType;

use App\GraphQL\Schema\blog\Type\CommentType;
use App\GraphQL\Schema\blog\Type\Field\HtmlField;
use App\GraphQL\Schema\blog\Type\SearchResultType;
use App\GraphQL\Schema\blog\Type\NodeType;
use App\GraphQL\Schema\blog\Type\StoryType;
use App\GraphQL\Schema\blog\Type\UserType;
use App\GraphQL\Schema\blog\Type\ImageType;


/**
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 */
class TypeRegistry extends TypeRegistryDefault
{
    private static $types = [];
    const LAZY_LOAD_GRAPHQL_TYPES = true;

    public static function user() : callable { return static::get(UserType::class); }
    public static function story() : callable { return static::get(StoryType::class); }
    public static function comment() : callable { return static::get(CommentType::class); }
    public static function image() : callable { return static::get(ImageType::class); }
    public static function node() : callable { return static::get(NodeType::class); }
    public static function mention() : callable { return static::get(SearchResultType::class); }
    public static function imageSizeEnum() : callable { return static::get(ImageSizeEnumType::class); }
    public static function contentFormatEnum() : callable { return static::get(ContentFormatEnum::class); }
    public static function email() : callable { return static::get(EmailType::class); }
    public static function url() : callable { return static::get(UrlType::class); }

    /**
     * @param $name
     * @param null $objectKey
     * @return array
     */
    public static function htmlField($name, $objectKey = null)
    {
        return HtmlField::build($name, $objectKey);
    }


}