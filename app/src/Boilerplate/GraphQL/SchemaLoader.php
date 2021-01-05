<?php

namespace App\Boilerplate\GraphQL;

use App\Boilerplate\GraphQL\Exception\FileNotFoundException;
use App\Boilerplate\FileCollector;


class SchemaLoader extends FileCollector
{

    public function lookup ($isNamespacedSchema = false) {
        parent::lookup();

        $loaderHash = hash('sha512', serialize($this), false);
        
        //foreach ($this->fileList as &$fileToInclude) {
        //    if ($fileToInclude['isPHP']) {
        //        //require_once($fileToInclude['fullPath']);
        //    }
        //}

        return $this;
    }
}