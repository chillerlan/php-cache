# chillerlan/php-cache

A psr/simple-cache implementation for PHP 7.2+.

[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Packagist downloads][downloads-badge]][downloads]
[![PayPal donate][donate-badge]][donate]

[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-cache.svg?style=flat-square
[packagist]: https://packagist.org/packages/chillerlan/php-cache
[license-badge]: https://img.shields.io/github/license/chillerlan/php-cache.svg?style=flat-square
[license]: https://github.com/chillerlan/php-cache/blob/master/LICENSE
[travis-badge]: https://img.shields.io/travis/chillerlan/php-cache.svg?style=flat-square
[travis]: https://travis-ci.org/chillerlan/php-cache
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/php-cache.svg?style=flat-square
[coverage]: https://codecov.io/github/chillerlan/php-cache
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/chillerlan/php-cache.svg?style=flat-square
[scrutinizer]: https://scrutinizer-ci.com/g/chillerlan/php-cache
[downloads-badge]: https://img.shields.io/packagist/dt/chillerlan/php-cache.svg?style=flat-square
[downloads]: https://packagist.org/packages/chillerlan/php-cache/stats
[donate-badge]: https://img.shields.io/badge/donate-paypal-ff33aa.svg?style=flat-square
[donate]: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WLYUNAT9ZTJZ4

## Features:
 - [PSR-16 simple-cache-implementation](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md)
 - persistent: File based, Memcached, Redis
 - non-persistent: Session, Memory 

## Requirements
 - **PHP 7.2+**
   - [Memcached](http://php.net/manual/en/book.memcached.php)
   - [Redis](https://github.com/phpredis/phpredis/)
   - [APCU](http://php.net/manual/en/book.apcu.php)
   
## Documentation
### Installation using [composer](https://getcomposer.org)
You can simply clone the repo and run `composer install` in the root directory. 
In case you want to include it elsewhere, just add the following to your *composer.json*:

(note: replace `dev-master` with a [version boundary](https://getcomposer.org/doc/articles/versions.md))
```json
{
	"require": {
		"php": ">=7.2.0",
		"chillerlan/php-cache": "dev-master"
	}
}
```

### Manual installation
Download the desired version of the package from [master](https://github.com/chillerlan/php-cache/archive/master.zip) or 
[release](https://github.com/chillerlan/php-cache/releases) and extract the contents to your project folder.After that:
  - run `composer install` to install the required dependencies and generate `/vendor/autoload.php`.
  - if you use a custom autoloader, point the namespace `chillerlan\SimpleCache` to the folder `src` of the package 

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
$cache = new FileCache(new CacheOptions(['filestorage' => __DIR__.'/../.cache']));

// Session
$cache = new SessionCache(new CacheOptions(['cachekey' => '_my_session_cache']));

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
