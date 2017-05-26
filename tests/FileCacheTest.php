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

use chillerlan\SimpleCache\Drivers\FileCacheDriver;

class FileCacheTest extends SimpleCacheTestAbstract{

	protected function setUp(){
		$this->cacheDriver = new FileCacheDriver(__DIR__.'/../.cache');

		parent::setUp();
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\SimpleCacheException
	 * @expectedExceptionMessage invalid cachedir "foo"
	 */
	public function testCacheDriverInvalidDir(){
		new FileCacheDriver('foo');
	}

}
