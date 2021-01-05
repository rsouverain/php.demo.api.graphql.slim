<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class ExtensionException
 * @see https://www.apollographql.com/docs/apollo-server/performance/apq/#verify
 * @package App\Boilerplate\GraphQL\Exception
 */
class ExtensionException extends GenericGraphQlException
{

    /** @var array|null  */
    protected $extensions = [];

    /**
     * ExtensionException constructor.
     * @param null $extensions
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($extensions = null, $message = null, $code = 500, \Exception $previous = null) {
        $this->isHttpCode = true;

        $this->extensions = [];
        if (is_array($extensions)) {
            $this->extensions = $extensions;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array|null
     */
    public function getExtensions ()
    {
        return $this->extensions;
    }
}
