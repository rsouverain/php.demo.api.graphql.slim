<?

namespace App\GraphQL\Boilerplate;

// use Symfony\Component\Cache\Adapter\FilesystemAdapter; // @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter; // @see https://symfony.com/doc/current/components/cache/adapters/redis_adapter.html

/**
 * PSR-6 Compatible Caching Utility
 * @see https://www.php-fig.org/psr/psr-6/
 * @see https://symfony.com/doc/current/components/cache.html#cache-component-psr6-caching
 * 
 * @usage
 * 
    // With Symfony Contracts...
    use App\GraphQL\Boilerplate\CacheManager;
    $memoization = CacheManager::getInstance();
    $value = $memoization->get('FOO', function (\Symfony\Contracts\Cache\ItemInterface $item) {
        $item->expiresAfter(10); //seconds 
        return 'BAR';
    });

 * @usage
 *
    // Without Contracts (pure PSR-6)...
    $memoization = CacheManager::getInstance();
    $foo = $memoization->getItem('FOO');
    if (!$foo->isHit()) {
        // foo item does not exist in the cache
        $foo->set('BAR')->expiresAfter(10);
        $memoization->save($foo);
    }
    // retrieve the value stored by the item
    $value = $foo->get();
 * 
 */
class CacheManager
{

    // protected $instanceId;
    protected static $instance;

    protected $redisClient = null;
    protected $redisAdapter = null;
    
    protected $cacheKeyPrefix = '';

    public static function defaultOptions () {
        return [
            // Enables or disables compression of items. This requires phpredis v4 or higher with LZF support enabled.
            'compression' => true,

            // Enables or disables lazy connections to the backend. It’s false by default when using this as a stand-alone component and true by default when using it inside a Symfony application.
            'lazy' => true,

            // Enables or disables use of persistent connections. A value of 0 disables persistent connections, and a value of 1 enables them.
            'persistent' => 1, 
            
            // Specifies the persistent id string to use for a persistent connection.
            'persistent_id' => str_replace('\\', '_', __CLASS__), 
            
            // Specifies the TCP-keepalive timeout (in seconds) of the connection. This requires phpredis v4 or higher and a TCP-keepalive enabled server. This option is useful in order to detect dead peers (clients that cannot be reached even if they look connected).
            'tcp_keepalive' => 40,
            
            // Specifies the time (in seconds) used to connect to a Redis server before the connection attempt times out.
            'timeout' => 20,
            
            // Specifies the time (in seconds) used when performing read operations on the underlying network resource before the operation times out.
            'read_timeout' => 5,
            
            // Specifies the delay (in milliseconds) between reconnection attempts in case the client loses connection with the server.
            'retry_interval' => 600,
        ];
    }

    public function __construct($cacheKeyPrefix = null)
    {
        // $this->instanceId = uuid_create(UUID_TYPE_RANDOM); // @see https://symfony.com/blog/introducing-the-new-symfony-uuid-polyfill
        $this->setCacheKeyPrefix(str_replace('\\', '.', __CLASS__), false);

        if ($cacheKeyPrefix) {
            $this->setCacheKeyPrefix($cacheKeyPrefix, true);
        }
    }

    public static function getInstance () {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function adapter () : AbstractAdapter
    {
        return $this->getRedisAdapter();
    }

    public function __invoke($invoked) : AbstractAdapter
    {
        return $this->adapter();
    }

    public function __call($name, $arguments = [])
    {
        if (!isset($arguments) || !is_array($arguments)) {
            $arguments = [];
        }
        return call_user_func_array([$this->adapter(), $name], $arguments);
    }

    public function setCacheKeyPrefix (string $prefix, bool $isAppendMode = true)
    {
        if ($isAppendMode) {
            $this->cacheKeyPrefix .= '--'.$prefix;
        }
        else {
            $this->cacheKeyPrefix = $prefix;
        }
        return $this;
    }

    public function setRedisAdapter (RedisAdapter $adapter)
    {
        if ($this->redisAdapter !== null) {
            throw new \Exception('Cannot set redis adapter when already initialized.');
        }
        $this->redisAdapter = $adapter;
        return $this;
    }
    
    public function setRedisClient (\Symfony\Component\Cache\Traits\RedisProxy  $client)
    {
        if ($this->redisClient !== null || $this->redisAdapter !== null) {
            throw new \Exception('Cannot set redis client when already initialized.');
        }
        $this->redisClient = $client;
        return $this;
    }

    protected function getRedisClient ($dsn = null, $options = null) : \Symfony\Component\Cache\Traits\RedisProxy
    {
        // pass a single DSN string to register a single server with the client
        if (!isset($dsn)) {
            $dsn = 'redis://gql_slim_redis:6379';
        }
        if ($this->redisClient === null) {
            $this->setRedisClient(
                RedisAdapter::createConnection(
                    $dsn,
                    is_array($options) ? array_merge(self::defaultOptions(), $options) : self::defaultOptions()
                )
            );
        }
        return $this->redisClient;
    }

    protected function getRedisAdapter () : RedisAdapter
    {
        // pass a single DSN string to register a single server with the client
        if ($this->redisAdapter === null) {
            $this->redisAdapter = new RedisAdapter(
                $this->getRedisClient(),
                // Namespace: the string prefixed to the keys of the items stored in this cache
                $this->cacheKeyPrefix,
                // TTL: the default lifetime (in seconds) for cache items that do not define their
                // own lifetime, with a value 0 causing items to be stored indefinitely (i.e.
                // until RedisAdapter::clear() is invoked or the server(s) are purged)
                3 * 60 * 60 // 3h
            );
        }
        return $this->redisAdapter;
    }

}
