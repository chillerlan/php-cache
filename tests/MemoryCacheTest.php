<?php
/**
 * Class MemoryCacheTest
 *
 * @filesource   MemoryCacheTest.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\MemoryCache;

class MemoryCacheTest extends NonpersistentTestAbstract{

	protected function setUp():void{
		$this->cache = new MemoryCache;

#		parent::setUp();
	}

}
