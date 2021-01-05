# OpcacheManager

## Namespace

`App\Boilerplate\OpcacheManager`

## Description



This class is an utility giving the developer an actionable API to interact with a performent caching mechanism using php's OPcache.

OPcache improves PHP performance by storing precompiled script bytecode in shared memory, thereby removing the need for PHP to load and parse scripts on each request.

The core of the technique is leveraging the PHP engine’s in-memory file caching (opcache) to cache application data in addition to code. HHVM has supported this technique for a few years, but PHP only recently started supporting it with the launch of PHP 7. The method still “works” in PHP < 7, it just isn’t fast.

The reason this method is faster than Redis, Memcache, APC, and other PHP caching solutions is the fact that all those solutions must serialize and unserialize objects, generally using PHP’s serialize or json_encode functions. By storing PHP objects in file cache memory across requests, we can avoid serialization completely!

:warning: Keep in mind that PHP file caching should primarily be used for **arrays** & **objects**, not strings, since there is no performance benefit for strings. In fact, APC is a tad bit faster when dealing with short strings, due to the slight overhead of calling PHP’s include() function.

* [500X Faster Caching than Redis/Memcache/APC in PHP](https://medium.com/@dylanwenzlau/500x-faster-caching-than-redis-memcache-apc-in-php-hhvm-dcd26e8447ad)
* [Read more about OPcache at PHP.net](https://www.php.net/manual/en/intro.opcache.php)

## Production Implementation

Your `opcache.memory_consumption` setting needs to be larger than the size of all your code files plus all the data you plan to store in the cache.

Your `opcache.max_accelerated_files` setting needs to be larger than your total number of code files plus the total number of keys you plan to cache.

> If those settings aren’t high enough, the cache will still work, but its performance may suffer.


The following `php.ini` settings are generally recommended as providing good performance

```ini
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
opcache.enable_cli=1
```

* [All settings](https://www.php.net/manual/en/opcache.configuration.php)

## Xdebug

If you want to use OPcache with » Xdebug, you must load OPcache before Xdebug.


## Usage

Instantiate:
```php
// main singleton instance
$opcache = OpcacheManager::getInstance();
// or custom instance
$opcache = new OpcacheManager('./cache/', 'opcache');
```

Create cache files using its cache key as filename, values should be php `object` or `array`:
```php
$opcache->set('foo', ['bar']);
$opcache->set('lorem', ['ipsum']);
$opcache->set('dolor', ['sit', 'amet']);
$opcache->delete('foo');
$opcache->clearAll();
```

Get file values by its key:
```php
$value = $opcache->get('lorem');
```

remove one file by its key:
```php
$isDeleted = $opcache->delete('lorem');
```

cleanup instantiated cache folder, removing all files:
```php
$opcache->clearAll();
// or
$opcache->clearTemporary();
$opcache->clearNonTemporary();
```
