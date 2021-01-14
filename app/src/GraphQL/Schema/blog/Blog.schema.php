<?php

use App\GraphQL\Schema\blog\Type\QueryTypeDemo;
use App\GraphQL\Schema\blog\TypeRegistry;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;

return (function () {
    /*
     * Schema Definition
     * @see http://webonyx.github.io/graphql-php/type-system/schema/
     */
    $config = SchemaConfig::create()
        ->setQuery(new QueryTypeDemo())
        ->setTypeLoader(function($name) {
            $typeRegistry = TypeRegistry::getInstance();
            return $typeRegistry->byTypeName($name);
        })
    ;

    return new Schema($config);
})();
