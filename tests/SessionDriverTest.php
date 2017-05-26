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

use chillerlan\SimpleCache\Drivers\SessionCacheDriver;

class SessionDriverTest extends NonpersistentTestAbstract{

	protected function setUp(){
		$this->cacheDriver = new SessionCacheDriver('_session_cache_test');

		parent::setUp();
	}

}
