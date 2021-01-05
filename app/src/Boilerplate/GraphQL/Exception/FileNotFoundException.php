<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class FileNotFoundException
 * @package App\Boilerplate\GraphQL\Exception
 */
class FileNotFoundException extends GenericGraphQlException
{

    /** @var string  */
    public $filePath = null;

    /**
     * FileNotFoundException constructor.
     * @param $filePath
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($filePath, $message = 'File Not Found', \Exception $previous = null) {
        $this->isHttpCode = false;
        $this->filePath = $filePath;
        parent::__construct($message, 404, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": {$this->message}, in path: {$this->filePath}";
    }
}
