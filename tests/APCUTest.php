<?php
/**
 * Class APCUTest
 *
 * @filesource   APCUTest.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\Drivers\APCUDriver;

class APCUTest extends SimpleCacheTestAbstract{

	protected function setUp(){
		$this->cacheDriver = new APCUDriver;

		parent::setUp();
	}

	public function testSetTTL(){
		$this->markTestIncomplete();
	}

	public function testSetMultipleTTL(){
		$this->markTestIncomplete();
	}
}