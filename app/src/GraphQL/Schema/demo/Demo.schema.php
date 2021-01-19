<?php

use App\GraphQL\Schema\demo\QueryType;
use App\GraphQL\Schema\demo\TypeRegistry;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;

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
