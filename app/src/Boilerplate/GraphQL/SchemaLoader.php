<?php

namespace App\Boilerplate\GraphQL;

use App\Boilerplate\FileCollector;

/**
 * Class SchemaLoader
 * @package App\Boilerplate\GraphQL
 */
class SchemaLoader extends FileCollector
{

    /**
     * @param bool $isNamespacedSchema
     * @return $this|FileCollector
     * @throws Exception\FileNotFoundException
     */
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
