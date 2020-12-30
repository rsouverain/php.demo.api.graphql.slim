<?php

namespace App\GraphQL\Exception;

use App\GraphQL\Exception\GenericGraphQlException;

/**
 * @see https://github.com/apollographql/apollo-link-persisted-queries
 */
class PersistedQueryNotSupportedException extends GenericGraphQlException
{
    public function __construct(Exception $previous = null) {
        $this->isHttpCode = true;
        parent::__construct('Persisted Queries Not Supported', 200, $previous);
    }
}