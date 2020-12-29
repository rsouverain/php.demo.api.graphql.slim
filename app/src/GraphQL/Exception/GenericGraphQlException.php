<?php

namespace App\GraphQL\Exception;

class  GenericGraphQlException extends \Exception
{
    public $isHttpCode = false;
    
    public function __construct($message, $code = 500, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}