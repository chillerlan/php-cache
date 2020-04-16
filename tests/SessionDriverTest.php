<?php
/**
 * Class SessionDriverTest
 *
 * @filesource   SessionDriverTest.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\{CacheOptions, SessionCache};
use Psr\SimpleCache\CacheException;

class SessionDriverTest extends NonpersistentTestAbstract{

	protected function setUp():void{
		$this->cache = new SessionCache(new CacheOptions(['cacheSessionkey' => '_session_cache_test']));
	}

	public function testEmptyKeyException(){
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('invalid session cache key');

		new SessionCache(new CacheOptions(['cacheSessionkey' => '']));
	}
}
