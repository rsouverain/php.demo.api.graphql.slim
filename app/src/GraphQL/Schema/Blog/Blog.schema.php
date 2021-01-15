<?php

use App\GraphQL\Schema\Blog\Type\QueryTypeDemo;
use App\GraphQL\Schema\Blog\TypeRegistry;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use App\GraphQL\Schema\Blog\Type\QueryType;

return (function () {
    /*
     * Schema Definition
     * @see http://webonyx.github.io/graphql-php/type-system/schema/
     */
    $config = SchemaConfig::create()
        ->setQuery(new QueryType())
        ->setTypeLoader(function($name) {
            $typeRegistry = TypeRegistry::getInstance();
            return $typeRegistry->byTypeName($name);
        })
    ;
    return new Schema($config);
})();
