<?php

namespace App\Boilerplate\GraphQL\Exception;

class FileNotFoundException extends GenericGraphQlException
{
    public $filePath = null;
    public function __construct($filePath, $message = 'File Not Found', Exception $previous = null) {
        $this->isHttpCode = false;
        $this->filePath = $filePath;
        parent::__construct($message, 404, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": {$this->message}, in path: {$this->filePath}";
    }
}