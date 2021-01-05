<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * @see https://www.apollographql.com/docs/apollo-server/performance/apq/#verify
 */
class PersistedQueryNotFoundException extends ExtensionException
{
    protected static $extension = 'persistedQuery';
    protected static $name = 'PersistedQueryNotFound';

    public function __construct($extensions = null, $queryHash = null, Exception $previous = null) {
        $this->isHttpCode = true;
        parent::__construct($extensions, self::$name, 200, $previous);

        if (!isset($this->extensions[self::$extension])) {
            $this->extensions[self::$extension] = [];
        }
        if (!isset($this->extensions[self::$extension]['sha256Hash'])) {
            $this->extensions[self::$extension]['sha256Hash'] = $queryHash;
        }
        if (!isset($this->extensions[self::$extension]['version'])) {
            $this->extensions[self::$extension]['version'] = 1;
        }
    }
}