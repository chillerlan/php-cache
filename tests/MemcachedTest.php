<?php
/**
 * Class MemcachedTest
 *
 * @filesource   MemcachedTest.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\MemcachedCache;
use Memcached;
use Psr\SimpleCache\CacheException;

class MemcachedTest extends SimpleCacheTestAbstract{

	protected function setUp():void{

		if(!extension_loaded('memcached')){
			$this->markTestSkipped('Memcached not installed/enabled');

			return;
		}

		$memcached = new Memcached('test');
		$memcached->addServer('localhost', 11211);

		$this->cache = new MemcachedCache($memcached);

#		parent::setUp();
	}

	public function testMemcachedDriverNoServerException(){
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('no memcache server available');

		new MemcachedCache(new Memcached);
	}

}
