<?php
/**
 * Class SessionDriverTest
 *
 * @filesource   SessionDriverTest.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\{CacheOptions, SessionCache};

class SessionDriverTest extends NonpersistentTestAbstract{

	protected function setUp(){
		$this->cache = new SessionCache(new CacheOptions(['cacheSessionkey' => '_session_cache_test']));

		parent::setUp();
	}

}
