<?php

namespace App\Boilerplate\GraphQL;

use App\Boilerplate\FileCollector;

use App\Boilerplate\GraphQL\Exception\FileNotFoundException;
use App\Boilerplate\OpcacheManager;
use GraphQL\Error\Error;
use \GraphQL\Type\Schema;
use GraphQL\Utils\AST;

/**
 * Class SchemaLoader
 * @package App\Boilerplate\GraphQL
 */
class SchemaLoader extends FileCollector
{

    /**
     * Only works if all our Schema Types are implementing "__set_state", not the case of graphql-php at the moment
     *
     * @return \GraphQL\Type\Schema|null
     * @throws Exception\FileNotFoundException
     */
    public function load()
    {
        $opcache = OpcacheManager::getInstance();
        $cacheKey = 'gql-schema-'.$this->getLookupHash();
        $schema = $opcache->get($cacheKey);
        if (!($schema instanceof Schema)) {
            $this->lookup();
            if (count($this->fileList) <= 0) {
                throw new FileNotFoundException('', 'Schema File Not Found');
            }
            $fileInfo = reset($this->fileList);
            $schema = require_once($fileInfo['fullPath']);
            $opcache->set($cacheKey, $schema);
        }

        return $schema;
    }


}
