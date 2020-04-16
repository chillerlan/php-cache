<?php
/**
 * Class NonpersistentTestAbstract
 *
 * @filesource   NonpersistentTestAbstract.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

abstract class NonpersistentTestAbstract extends SimpleCacheTestAbstract{

	public function testGet(){
		$this->cache->set('hello', 'whatever');

		self::assertSame('whatever', $this->cache->get('hello'));
		self::assertNull($this->cache->get('foo'));
		self::assertSame('default', $this->cache->get('foo', 'default'));

	}

	public function testDelete(){
		$this->cache->set('hello', 'whatever');

		self::assertTrue($this->cache->has('hello'));
		self::assertTrue($this->cache->delete('hello'));
		self::assertFalse($this->cache->has('hello'));
	}

	public function testHas(){
		$this->cache->set('hello', 'whatever');

		self::assertTrue($this->cache->has('hello'));
	}

	public function testGetMultiple(){
		$this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']);

		self::assertSame(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3'], $this->cache->getMultiple(['k1', 'k2', 'k3']));
	}

	public function testDeleteMultiple(){
		$this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']);

		$this->cache->deleteMultiple(['k1', 'k3']);
		self::assertFalse($this->cache->has('k1'));
		self::assertFalse($this->cache->has('k3'));
		self::assertTrue($this->cache->has('k2'));
	}

	public function testClear(){
		$this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2']);

		self::assertTrue($this->cache->has('k2'));
		self::assertTrue($this->cache->clear());
		self::assertFalse($this->cache->has('k2'));
	}

}
