<?php
/**
 * Class MemoryCacheTest
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\MemoryCache;

class MemoryCacheTest extends NonpersistentTestAbstract{

	protected function setUp():void{
		parent::setUp();

		$this->cache = new MemoryCache;
	}

}
