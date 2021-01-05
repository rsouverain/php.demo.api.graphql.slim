<?

namespace App\Boilerplate\GraphQL;

use App\Boilerplate\CacheManager;

use App\Boilerplate\GraphQL\Exception\PersistedQueryNotSupportedException;
use App\Boilerplate\GraphQL\Exception\PersistedQueryNotFoundException;

/**
 * Improve network performance by sending smaller requests
 * 
 * With Automatic Persisted Queries, the ID is a deterministic hash
 * of the input query, so we don't need a complex build step to share the ID between clients and servers.
 * If a server doesn't know about a given hash, the client can expand the query for it;
 * The server caches that mapping.
 * 
 * @see https://www.apollographql.com/docs/apollo-server/performance/apq/
 */
class AutomaticPersistedQueries
{

    protected static $extensionName = 'persistedQuery';
    protected static $cacheKeyPrefix = 'graphQL_APQ';
    public $isEnabled;

    public function __construct($cacheTTL = 300)
    {
        $this->isEnabled = true; // @TODO: optionize
    }


    /**
     * @return bool|string Boolean indicates if is cached, string is the gql query
     */
    public function onRequestRecieved ($query = null, $extensions = null, $variables = null)
    {
        if (!$this->hasCurrentExtension($extensions)) {
            return $query;
        }
        elseif (!$this->isEnabled) {
            throw new PersistedQueryNotSupportedException();
        }

        $queryHash = $this->getQueryHash($extensions);
        if ($queryHash === null) {
            return $query;
        }

        if ($query) {
            // A GQL Query is available
            return $this->setQueryCacheByHash($queryHash, $query);
        }

        $persistedQuery = $this->lookupQueryCacheByHash($queryHash);
        if ($persistedQuery === null) {
            throw new PersistedQueryNotFoundException($extensions, $queryHash);
        }
        return $persistedQuery;
    }

    private function hasCurrentExtension (array $extensions)
    {
        return in_array(self::$extensionName, $extensions);
    }

    private function hasQueryHash (array $extensions)
    {
        return ($this->hasCurrentExtension($extensions) && isset($extensions[self::$extensionName]['sha256Hash']));
    }

    private function getQueryHash (array $extensions)
    {
        if ($this->hasQueryHash($extensions)) {
            return $extensions[self::$extensionName]['sha256Hash'];
        }
        return null;
    }

    private function generateCacheKey (string $queryHash)
    {
        return '${self::$cacheKeyPrefix}_${queryHash}';
    }

    /**
     * @param String queryHash
     * @param String query (gql)
     * @param Boolean force when true, set the cache even if it already exist.
     * @return Boolean acknowledgement
     */
    private function setQueryCacheByHash (string $queryHash, string $query, bool $force = true)
    {
        try {
            $lookupKey = $this->generateCacheKey($queryHash);
        }
        catch (\Exception $ex) {
            // @TODO: Improve logs on failure
            return false;
        }
        return true;
    }
    
    private function lookupQueryCacheByHash (string $queryHash)
    {
        // @TODO: Improve logs on failure
        $lookupKey = $this->generateCacheKey($queryHash);
        $cache = CacheManager::getInstance();
        $cacheItem = $cache->getItem($lookupKey);
        if ($cacheItem->isHit()) {
            // lookupKey item exists in the cache
            return $cacheItem->get();
        }
        return null;
    }
}
