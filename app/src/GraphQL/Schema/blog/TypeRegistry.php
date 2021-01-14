<?php

namespace App\GraphQL\Schema\blog;

use Exception;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

use App\Boilerplate\GraphQL\TypeRegistryDefault;

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
    public function user () : callable {
        return $this->get(UserType::class);
    }

    public function story () : callable {
        return $this->get(StoryType::class);
    }

    public function comment () : callable {
        return $this->get(CommentType::class);
    }

    public function image () : callable {
        return $this->get(ImageType::class);
    }

    public function node () : callable {
        return $this->get(NodeType::class);
    }

    public function mention () : callable {
        return $this->get(SearchResultType::class);
    }

    public function imageSizeEnum () : callable {
        return $this->get(ImageSizeEnumType::class);
    }

    public function contentFormatEnum () : callable {
        return $this->get(ContentFormatEnum::class);
    }

    public function email () : callable {
        return $this->get(EmailType::class);
    }

    public function url () : callable {
        return $this->get(UrlType::class);
    }

    public function htmlField ($name, $objectKey = null)
    {
        return HtmlField::build($name, $objectKey);
    }


}
