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

use chillerlan\SimpleCache\APCUCache;

use function extension_loaded;

class APCUTest extends SimpleCacheTestAbstract{

	protected function setUp():void{
		parent::setUp();

		if(!extension_loaded('apcu')){
			$this->markTestSkipped('APCU not installed/enabled');
		}

		$this->cache = new APCUCache;
	}

	public function testSetTTL():void{
		$this->markTestIncomplete();
	}

	public function testSetMultipleTTL():void{
		$this->markTestIncomplete();
	}
}
