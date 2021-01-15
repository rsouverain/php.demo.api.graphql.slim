<?php

namespace App\GraphQL\Schema\Blog;

use Exception;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

use App\Boilerplate\GraphQL\TypeRegistryDefault;

use App\GraphQL\Schema\_common\Type\Scalar\UrlType;
use App\GraphQL\Schema\_common\Type\Scalar\EmailType;
use App\GraphQL\Schema\Blog\Type\Enum\ContentFormatEnum;
use App\GraphQL\Schema\Blog\Type\Enum\ImageSizeEnumType;

use App\GraphQL\Schema\Blog\Type\Field\HtmlField;
use App\GraphQL\Schema\Blog\Type\SearchResultType;
use App\GraphQL\Schema\Blog\Type\NodeType;

use App\GraphQL\Schema\Blog\Domain\User\UserType;
use App\GraphQL\Schema\Blog\Domain\Comment\CommentType;
use App\GraphQL\Schema\Blog\Domain\Story\StoryType;
use App\GraphQL\Schema\Blog\Domain\Image\ImageType;



/**
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 */
class TypeRegistry extends TypeRegistryDefault
{
    public static function user () : callable {
        return self::getInstance()->get(UserType::class);
    }

    public static function story () : callable {
        return self::getInstance()->get(StoryType::class);
    }

    public static function comment () : callable {
        return self::getInstance()->get(CommentType::class);
    }

    public static function image () : callable {
        return self::getInstance()->get(ImageType::class);
    }

    public static function node () : callable {
        return self::getInstance()->get(NodeType::class);
    }

    public static function mention () : callable {
        return self::getInstance()->get(SearchResultType::class);
    }

    public static function imageSizeEnum () : callable {
        return self::getInstance()->get(ImageSizeEnumType::class);
    }

    public static function contentFormatEnum () : callable {
        return self::getInstance()->get(ContentFormatEnum::class);
    }

    public static function email () : callable {
        return self::getInstance()->get(EmailType::class);
    }

    public static function url () : callable {
        return self::getInstance()->get(UrlType::class);
    }

    public static function htmlField ($name, $objectKey = null)
    {
        return HtmlField::build($name, $objectKey);
    }
}
