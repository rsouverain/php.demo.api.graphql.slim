<?php

namespace App\Boilerplate\GraphQL\Exception;

use GraphQL\Error\ClientAware;

/**
 * Class GenericGraphQlException
 * @package App\Boilerplate\GraphQL\Exception
 */
class  GenericGraphQlException extends \Exception implements ClientAware
{
    /** @var bool  */
    public $isHttpCode = false;

    /**
     * GenericGraphQlException constructor.
     * @param $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code = 500, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return get_class($this) . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * @return bool
     */
    public function isClientSafe()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return get_class($this);
    }
}
