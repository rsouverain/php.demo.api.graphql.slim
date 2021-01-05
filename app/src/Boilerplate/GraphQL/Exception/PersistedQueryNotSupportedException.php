<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class PersistedQueryNotSupportedException
 * @package App\Boilerplate\GraphQL\Exception
 */
class PersistedQueryNotSupportedException extends GenericGraphQlException
{
    /**
     * PersistedQueryNotSupportedException constructor.
     * @param \Exception|null $previous
     */
    public function __construct(\Exception $previous = null) {
        $this->isHttpCode = true;
        parent::__construct('Persisted Queries Not Supported', 200, $previous);
    }
}
