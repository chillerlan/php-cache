<?php
/**
 * Class RedisTest
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 * @phan-file-suppress PhanUndeclaredClassMethod, PhanUndeclaredClassConstant
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\RedisCache;
use Redis;

use function defined, extension_loaded;

class RedisTest extends SimpleCacheTestAbstract{

	protected function setUp():void{
		parent::setUp();

		if(!extension_loaded('redis')){
			$this->markTestSkipped('Redis not installed/enabled');
		}

		$redis = new Redis;
		$redis->pconnect('127.0.0.1', 6379);

		if(defined('Redis::SERIALIZER_IGBINARY') && extension_loaded('igbinary')){
			/** @phan-suppress-next-line PhanUndeclaredConstantOfClass */
			$redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
		}

		$this->cache = new RedisCache($redis);
	}

}
