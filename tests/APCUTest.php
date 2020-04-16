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

class APCUTest extends SimpleCacheTestAbstract{

	protected function setUp():void{

		if(!\extension_loaded('apcu')){
			$this->markTestSkipped('APCU not installed/enabled');

			return;
		}

		$this->cache = new APCUCache;
	}

}
