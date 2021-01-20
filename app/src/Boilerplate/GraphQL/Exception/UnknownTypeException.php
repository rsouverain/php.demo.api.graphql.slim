<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class UnknownTypeException
 * @package App\Boilerplate\GraphQL\Exception
 */
class UnknownTypeException extends GenericGraphQlException
{
    protected $typeName;

    /**
     * UnknownTypeException constructor.
     * @param string $typeName
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct(string $typeName, $message = 'Unknown Graphql Type', \Exception $previous = null) {
        $this->isHttpCode = true;
        $this->typeName = $typeName;
        parent::__construct($message. ' : ' . $typeName, 403, $previous);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": {$this->message}";
    }
}
