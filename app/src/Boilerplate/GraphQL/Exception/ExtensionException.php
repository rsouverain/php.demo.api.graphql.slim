<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * @see https://www.apollographql.com/docs/apollo-server/performance/apq/#verify
 */
class ExtensionException extends GenericGraphQlException
{

    protected $extensions;

    public function __construct($extensions = null, $message = null, $code = 500, Exception $previous = null) {
        $this->isHttpCode = true;

        $this->extensions = [];
        if (is_array($extensions)) {
            $this->extensions = $extensions;
        }

        parent::__construct($message, $code, $previous);
    }

    public function getExtensions ()
    {
        return $this->extensions;
    }
}