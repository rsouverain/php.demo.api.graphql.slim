<?php

namespace App\GraphQL\Exception;

use GraphQL\Error\ClientAware;

class  GenericGraphQlException extends \Exception implements ClientAware
{
    public $isHttpCode = false;
    
    public function __construct($message, $code = 500, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return get_class($this) . ": [{$this->code}]: {$this->message}\n";
    }

    public function isClientSafe()
    {
        return true;
    }

    public function getCategory()
    {
        return get_class($this);
    }
}