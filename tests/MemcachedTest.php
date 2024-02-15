<?php
/**
 * Class MemcachedTest
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 * @phan-file-suppress PhanUndeclaredClassMethod
 */

declare(strict_types=1);

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\MemcachedCache;
use Memcached;
use Psr\SimpleCache\CacheException;

class MemcachedTest extends SimpleCacheTestAbstract{

	protected function setUp():void{

		if(!extension_loaded('memcached')){
			$this->markTestSkipped('Memcached not installed/enabled');
		}

		parent::setUp();

		$memcached = new Memcached('test');
		$memcached->addServer('localhost', 11211);

		$this->cache = new MemcachedCache($memcached);
	}

	public function testMemcachedDriverNoServerException():void{
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('no memcache server available');

		$this->cache = new MemcachedCache(new Memcached);
	}

}
