<?php
/**
 * Class RedisTest
 *
 * @filesource   RedisTest.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\RedisCache;
use Redis;

class RedisTest extends SimpleCacheTestAbstract{

	protected function setUp(){

		if(!extension_loaded('redis')){
			$this->markTestSkipped('Redis not installed/enabled');

			return $this;
		}

		$redis = new Redis();
		$redis->pconnect('127.0.0.1', 6379);

		if (defined('Redis::SERIALIZER_IGBINARY') && extension_loaded('igbinary')) {
			$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
		}

		$this->cache = new RedisCache($redis);

		parent::setUp();
	}

}
