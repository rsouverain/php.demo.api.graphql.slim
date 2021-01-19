<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * @package App\Boilerplate\GraphQL\Exception
 */
class InvalidDataloaderResultCountException extends GenericGraphQlException
{
    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = 'Invalid Dataloader Result Count', \Exception $previous = null) {
        $this->isHttpCode = false;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": {$this->message}";
    }
}
