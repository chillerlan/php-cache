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
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\MemcachedCache;
use Memcached;

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

	/**
	 * @expectedException \chillerlan\SimpleCache\CacheException
	 * @expectedExceptionMessage no memcache server available
	 */
	public function testMemcachedDriverNoServer(){
		new MemcachedCache(new Memcached);
	}

}
