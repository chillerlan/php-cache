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

use chillerlan\SimpleCache\Drivers\MemcachedDriver;
use Memcached;

class MemcachedTest extends SimpleCacheTestAbstract{

	protected function setUp(){

		if(!extension_loaded('memcached')){
			$this->markTestSkipped('Memcached not installed/enabled');

			return $this;
		}

		$memcached = new Memcached('test');
		$memcached->addServer('localhost', 11211);

		$this->cacheDriver = new MemcachedDriver($memcached);

		parent::setUp();
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\SimpleCacheException
	 * @expectedExceptionMessage no memcache server available
	 */
	public function testMemcachedDriverNoServer(){
		new MemcachedDriver(new Memcached);
	}

}
