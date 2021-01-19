<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class DataLoaderNotFoundException
 * @package App\Boilerplate\GraphQL\Exception
 */
class DataLoaderNotFoundException extends GenericGraphQlException
{
    /**
     * DataLoaderNotFoundException constructor.
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = 'Dataloader Not Found', \Exception $previous = null) {
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
