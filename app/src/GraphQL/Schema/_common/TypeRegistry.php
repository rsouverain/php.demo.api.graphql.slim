<?php

namespace App\GraphQL\Schema\_common;

use App\Boilerplate\GraphQL\TypeRegistryDefault;
use App\GraphQL\Schema\_common\Interfaces\DataNodeInterface;
use App\GraphQL\Schema\_common\Type\Scalar\DateTime;
use App\GraphQL\Schema\_common\Type\Scalar\EmailType;
use App\GraphQL\Schema\_common\Type\Scalar\UrlType;


/**
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 */
class TypeRegistry extends TypeRegistryDefault
{
    public function DataNodeInterface () : callable {
        return $this->get(DataNodeInterface::class);
    }

    public function Email () : callable {
        return $this->get(EmailType::class);
    }

    public function Url () : callable {
        return $this->get(UrlType::class);
    }

    public function DateTime () {
        return $this->get(DateTime::class);
    }

}
