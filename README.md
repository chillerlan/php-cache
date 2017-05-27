# codemasher/php-cache

[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Code Climate][codeclimate-badge]][codeclimate]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-cache.svg
[packagist]: https://packagist.org/packages/chillerlan/php-cache
[license-badge]: https://img.shields.io/packagist/l/chillerlan/php-cache.svg
[license]: https://github.com/codemasher/php-cache/blob/master/LICENSE
[travis-badge]: https://img.shields.io/travis/codemasher/php-cache.svg
[travis]: https://travis-ci.org/codemasher/php-cache
[coverage-badge]: https://img.shields.io/codecov/c/github/codemasher/php-cache.svg
[coverage]: https://codecov.io/github/codemasher/php-cache
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/codemasher/php-cache.svg
[scrutinizer]: https://scrutinizer-ci.com/g/codemasher/php-cache
[codeclimate-badge]: https://img.shields.io/codeclimate/github/codemasher/php-cache.svg
[codeclimate]: https://codeclimate.com/github/codemasher/php-cache

## Features:
 - [PSR-16 simple-cache-implementation](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md)
 - persistent: File based, Memcached, Redis
 - non-persistent: Session, Memory 

## Requirements
 - **PHP 7+**
 - Memcached extension (optional)
 - Redis extension (optional)
 
## Documentation
### Installation using [composer](https://getcomposer.org)
You can simply clone the repo and run `composer install` in the root directory. 
In case you want to include it elsewhere, just add the following to your *composer.json*:
```json
{
	"require": {
		"php": ">=7.0.3",
		"chillerlan/php-cache": "dev-master"
	}
}
```

### Manual installation
Download the desired version of the package from [master](https://github.com/codemasher/php-cache/archive/master.zip) or 
[release](https://github.com/codemasher/php-cache/releases) and extract the contents to your project folder. 
Point the namespace `chillerlan\SimpleCache` to the folder `src` of the package.

Profit!

### Usage
Just invoke a `Cache` instance with the desired `CacheDriverInterface` like so:
```php
// Redis
$redis = new Redis();
$redis->pconnect('127.0.0.1', 6379);
		
$cacheDriver = new RedisDriver($redis);

// Memcached
$memcached = new Memcached('test');
$memcached->addServer('localhost', 11211);

$cacheDriver = new MemcachedDriver($memcached);

// File
$cacheDriver = new FileCacheDriver(__DIR__.'/../.cache');

// Session
$cacheDriver = new SessionCacheDriver('_session_cache');

// Memory
$cacheDriver = new MemoryCacheDriver;

// load the cache instance
$cache = new Cache($cacheDriver);

```

#### Methods
See: [`Psr\SimpleCache\CacheInterface`](https://github.com/php-fig/simple-cache/blob/master/src/CacheInterface.php)

```php
$cache->get(string $key, $default = null); // -> mixed
$cache->set(string $key, $value, int $ttl = null):bool
$cache->delete(string $key):bool
$cache->has(string $key):bool
$cache->clear():bool
$cache->getMultiple(array $keys, $default = null):array // -> mixed[]
$cache->setMultiple(array $values, int $ttl = null):bool
$cache->deleteMultiple(array $keys):bool
```

## Disclaimer!
I don't take responsibility for molten memory modules, bloated hard disks, self-induced DoS, broken screens etc. Use at your own risk! :see_no_evil:
