<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class FileNotFoundException
 * @package App\Boilerplate\GraphQL\Exception
 */
class AccessDeniedException extends GenericGraphQlException
{
    /**
     * AccessDeniedException constructor.
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message = 'Access Denied', \Exception $previous = null) {
        $this->isHttpCode = true;
        parent::__construct($message, 403, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": {$this->message}, in path: {$this->filePath}";
    }
}
