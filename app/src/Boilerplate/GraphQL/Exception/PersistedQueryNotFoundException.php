<?php

namespace App\Boilerplate\GraphQL\Exception;

/**
 * Class PersistedQueryNotFoundException
 * @package App\Boilerplate\GraphQL\Exception
 * @see https://www.apollographql.com/docs/apollo-server/performance/apq/#verify
 */
class PersistedQueryNotFoundException extends ExtensionException
{
    /** @var string  */
    protected static $extension = 'persistedQuery';

    /** @var string  */
    protected static $name = 'PersistedQueryNotFound';

    /**
     * PersistedQueryNotFoundException constructor.
     * @param null $extensions
     * @param null $queryHash
     * @param \Exception|null $previous
     */
    public function __construct($extensions = null, $queryHash = null, \Exception $previous = null) {
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
