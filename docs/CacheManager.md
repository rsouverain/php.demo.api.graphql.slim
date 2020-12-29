# Cache Manager

## Namespace

`App\GraphQL\Boilerplate\CacheManager`

## Description

This class is an utility giving the developer an actionnable API to interract with a performent caching mecanism like Redis.

The basic use will be to store key/value pairs with a Time To Live (TTL) expiration time.

## Usages

### With Symfony Contracts

* [Read more](https://symfony.com/doc/current/components/cache.html#cache-contracts)

```php
use App\GraphQL\Boilerplate\CacheManager;
$memoization = CacheManager::getInstance();
$value = $memoization->get('FOO', function (\Symfony\Contracts\Cache\ItemInterface $item) {
    $item->expiresAfter(10); //seconds 
    return 'BAR';
});
```

### Without Symfony Contracts (pure PSR-6)

* [Read more](https://symfony.com/doc/current/components/cache.html#generic-caching-psr-6)

```php
$memoization = CacheManager::getInstance();
$foo = $memoization->getItem('FOO');
if (!$foo->isHit()) {
    // foo item does not exist in the cache
    $foo->set('BAR')->expiresAfter(10);
    $memoization->save($foo);
}
// retrieve the value stored by the item
$value = $foo->get();
```

> TODO: Improving this section of documentation with more detailled and explained use cases.

----
* Back to [README](../README.md)
