# chillerlan/php-cache

A psr/simple-cache implementation for PHP 8.1+.

[![PHP Version Support][php-badge]][php]
[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Coverage][coverage-badge]][coverage]
[![Codacy][codacy-badge]][codacy]
[![Packagist downloads][downloads-badge]][downloads]<br/>
[![Continuous Integration][gh-action-badge]][gh-action]

[php-badge]: https://img.shields.io/packagist/php-v/chillerlan/php-cache?logo=php&color=8892BF
[php]: https://www.php.net/supported-versions.php
[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-cache.svg?logo=packagist
[packagist]: https://packagist.org/packages/chillerlan/php-cache
[license-badge]: https://img.shields.io/github/license/chillerlan/php-cache.svg
[license]: https://github.com/chillerlan/php-cache/blob/master/LICENSE
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/php-cache.svg?logo=codecov
[coverage]: https://codecov.io/github/chillerlan/php-cache
[codacy-badge]: https://img.shields.io/codacy/grade/69b19ba81ae6492f973c4f05b92884aa/main?logo=codacy
[codacy]: https://app.codacy.com/gh/chillerlan/php-cache/dashboard?branch=main
[downloads-badge]: https://img.shields.io/packagist/dt/chillerlan/php-cache.svg?logo=packagist
[downloads]: https://packagist.org/packages/chillerlan/php-cache/stats
[gh-action-badge]: https://github.com/chillerlan/php-cache/workflows/Continuous%20Integration/badge.svg
[gh-action]: https://github.com/chillerlan/php-cache/actions

## Features:
- [PSR-16 simple-cache-implementation](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md)
  - persistent: File based, Memcached, Redis
  - non-persistent: Session, Memory

## Requirements
- **PHP 8.1+**
  - optionally one of the following extensions
    - [Memcached](https://www.php.net/manual/en/book.memcached.php)
    - [Redis](https://github.com/phpredis/phpredis/)
    - [APCU](https://www.php.net/manual/en/book.apcu.php)

## Documentation
### Installation using [composer](https://getcomposer.org)
You can simply clone the repo and run `composer install` in the root directory.
In case you want to include it elsewhere, just add the following to your *composer.json*:

(note: replace `dev-main` with a [version constraint](https://getcomposer.org/doc/articles/versions.md#writing-version-constraints),
 e.g. `^4.1` - see [releases](https://github.com/chillerlan/php-cache/releases) for valid versions)
```json
{
	"require": {
		"php": "^8.1",
		"chillerlan/php-cache": "dev-main"
	}
}
```

Installation via terminal: `composer require chillerlan/php-cache`

Profit!

### Usage
Just invoke a cache instance with the desired `CacheInterface` like so:
```php
// Redis
$redis = new Redis;
$redis->pconnect('127.0.0.1', 6379);

$cache = new RedisCache($redis);

// Memcached
$memcached = new Memcached('myCacheInstance');
$memcached->addServer('localhost', 11211);

$cache = new MemcachedCache($memcached);

// APCU
$cache = new APCUCache;

// File
$cache = new FileCache(new CacheOptions(['cacheFilestorage' => __DIR__.'/../.cache']));

// Session
$cache = new SessionCache(new CacheOptions(['cacheSessionkey' => '_my_session_cache']));

// Memory
$cache = new MemoryCache;
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
