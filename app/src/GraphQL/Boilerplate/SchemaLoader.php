<?php

namespace App\GraphQL\Boilerplate;

use App\GraphQL\Exception\FileNotFoundException;
use App\GraphQL\Boilerplate\FileCollector;


class SchemaLoader extends FileCollector
{
    //public function lookup () {
    //    parent::lookup();
    //    
    //    foreach ($this->fileList as &$fileToInclude) {
    //        if ($fileToInclude['isPHP']) {
    //            //require_once($fileToInclude['fullPath']);
    //        }
    //    }
    //    //spl_autoload_register(function ($class) use ($this) {
    //    //    //include 'classes/' . $class . '.class.php';
    //    //});
    //    //new Toto();
    //    return $this;
    //}
}