<?php
/**
 * Class SessionDriverTest
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\SessionCache;
use Psr\SimpleCache\CacheException;

class SessionDriverTest extends NonpersistentTestAbstract{

	protected function setUp():void{
		parent::setUp();

		$this->options->cacheSessionkey = '_session_cache_test';

		$this->cache = new SessionCache($this->options);
	}

	public function testEmptyKeyException():void{
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('invalid session cache key');

		$this->options->cacheSessionkey = '';

		$this->cache = new SessionCache($this->options);
	}
}
