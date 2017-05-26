<?php
/**
 *
 * @filesource   NonpersistentTestAbstract.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

/**
 * Class NonpersistentTestAbstract
 */
abstract class NonpersistentTestAbstract extends SimpleCacheTestAbstract{

	public function testGet(){
		$this->cache->set('hello', 'whatever');

		$this->assertSame('whatever', $this->cache->get('hello'));
		$this->assertNull($this->cache->get('foo'));
		$this->assertSame('default', $this->cache->get('foo', 'default'));

	}

	public function testDelete(){
		$this->cache->set('hello', 'whatever');

		$this->assertTrue($this->cache->has('hello'));
		$this->assertTrue($this->cache->delete('hello'));
		$this->assertFalse($this->cache->has('hello'));
	}

	public function testHas(){
		$this->cache->set('hello', 'whatever');

		$this->assertTrue($this->cache->has('hello'));
	}

	public function testGetMultiple(){
		$this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']);

		$this->assertSame(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3'], $this->cache->getMultiple(['k1', 'k2', 'k3']));
	}

	public function testDeleteMultiple(){
		$this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']);

		$this->cache->deleteMultiple(['k1', 'k3']);
		$this->assertFalse($this->cache->has('k1'));
		$this->assertFalse($this->cache->has('k3'));
		$this->assertTrue($this->cache->has('k2'));
	}

	public function testClear(){
		$this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2']);

		$this->assertTrue($this->cache->has('k2'));
		$this->assertTrue($this->cache->clear());
		$this->assertFalse($this->cache->has('k2'));
	}

}
