<?php

namespace App\GraphQL\Boilerplate;

use App\GraphQL\Exception\FileNotFoundException;
use App\GraphQL\Boilerplate\FileCollector;


class SchemaLoader extends FileCollector
{
    public function lookup () {
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