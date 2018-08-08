<?php
/**
 * Class FileCacheTest
 *
 * @filesource   FileCacheTest.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\{CacheOptions, Drivers\FileCacheDriver};

class FileCacheTest extends SimpleCacheTestAbstract{

	protected function setUp(){
		$this->cacheDriver = new FileCacheDriver(new CacheOptions([
			'cacheFilestorage' => __DIR__.'/../.cache\\/', /* some additional trailing slashes... */
		]));

		parent::setUp();
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\CacheException
	 * @expectedExceptionMessage invalid cachedir
	 */
	public function testCacheDriverInvalidDir(){
		new FileCacheDriver(new CacheOptions(['cacheFilestorage' => 'foo']));
	}

}
