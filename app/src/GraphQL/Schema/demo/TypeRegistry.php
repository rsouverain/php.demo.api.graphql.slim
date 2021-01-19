<?php

namespace App\GraphQL\Schema\demo;

use App\GraphQL\Schema\demo\User\UserAccount;
use App\GraphQL\Schema\demo\User\UserAccountJwt;
use App\GraphQL\Schema\demo\User\UserNamespaceQuery;

/**
 * Acts as a registry and factory for your types.
 *
 * As simplistic as possible for the sake of clarity of this example.
 * Your own may be more dynamic (or even code-generated).
 */
class TypeRegistry extends \App\GraphQL\Schema\_common\TypeRegistry
{

    // Users
    public function UserNamespaceQuery () : callable {
        return $this->get(UserNamespaceQuery::class);
    }

    /**
     * @return UserAccount
     * @throws \Exception
     */
    public function UserAccount () : callable {
        return $this->get(UserAccount::class);
    }


    public function UserAccountJwt () : callable {
        return $this->get(UserAccountJwt::class);
    }

}
