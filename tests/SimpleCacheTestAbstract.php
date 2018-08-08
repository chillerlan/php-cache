<?php
/**
 * Class SimpleCacheTestAbstract
 *
 * @filesource   SimpleCacheTestAbstract.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\Cache;
use chillerlan\SimpleCache\Drivers\CacheDriverInterface;
use PHPUnit\Framework\TestCase;

abstract class SimpleCacheTestAbstract extends TestCase{

	/**
	 * @var \chillerlan\SimpleCache\Drivers\CacheDriverInterface
	 */
	protected $cacheDriver;

	/**
	 * @var \chillerlan\SimpleCache\Cache
	 */
	protected $cache;

	protected function setUp(){
		$this->cache = new Cache($this->cacheDriver);
	}

	public function testInstance(){
		$this->assertInstanceOf(CacheDriverInterface::class, $this->cacheDriver);
		$this->assertInstanceOf(Cache::class, $this->cache);
	}

	public function testSet(){
		$this->assertTrue($this->cache->set('hello', 'whatever'));
		$this->assertTrue($this->cache->set('42', 'yep'));
	}

	public function testSetTTL(){
		$this->assertTrue($this->cache->set('what', 'nope', new \DateInterval('PT2S')));
		$this->assertTrue($this->cache->set('oh', 'wait', 2));
		$this->assertSame('nope', $this->cache->get('what'));
		$this->assertSame('wait', $this->cache->get('oh'));

		sleep(3);

		$this->assertFalse($this->cache->has('what'));
		$this->assertFalse($this->cache->has('oh'));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid ttl
	 */
	public function testSetInvalidTTL(){
		$this->cache->set('.', '', new \stdClass);
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "42"
	 */
	public function testSetInvalidKey(){
		$this->assertTrue($this->cache->set(42, 'nope'));
	}

	public function testGet(){
		$this->assertSame('whatever', $this->cache->get('hello'));
		$this->assertNull($this->cache->get('foo'));
		$this->assertSame('default', $this->cache->get('foo', 'default'));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "42"
	 */
	public function testGetInvalidKey(){
		$this->cache->get(42);
	}

	public function testHas(){
		$this->assertTrue($this->cache->has('hello'));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "42"
	 */
	public function testHasInvalidKey(){
		$this->cache->has(42);
	}

	public function testDelete(){
		$this->assertTrue($this->cache->delete('hello'));
		$this->assertFalse($this->cache->delete('hello'));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "42"
	 */
	public function testDeleteInvalidKey(){
		$this->cache->delete(42);
	}

	public function testSetMultiple(){
		$this->assertTrue($this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']));
	}

	public function testSetMultipleTTL(){
		$this->assertTrue($this->cache->setMultiple(['k1ttl' => 'v1ttl'], new \DateInterval('PT2S')));
		$this->assertTrue($this->cache->setMultiple(['k2ttl' => 'v2ttl'], 2));
		$this->assertSame(['k1ttl' => 'v1ttl', 'k2ttl' => 'v2ttl'], $this->cache->getMultiple(['k1ttl', 'k2ttl']));

		sleep(3);

		$this->assertSame(['k1ttl' => null, 'k2ttl' => null], $this->cache->getMultiple(['k1ttl', 'k2ttl']));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid ttl
	 */
	public function testSetMultipleInvalidTTL(){
		$this->cache->setMultiple(['.' => ''], new \stdClass);
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid data
	 */
	public function testSetMultipleInvalidData(){
		$this->cache->setMultiple('foo');
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "0"
	 */
	public function testSetMultipleInvalidKey(){
		$this->cache->setMultiple(['foo']);
	}

	public function testGetMultiple(){
		$this->assertSame(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3'], $this->cache->getMultiple(['k1', 'k2', 'k3']));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid data
	 */
	public function testGetMultipleInvalidData(){
		$this->cache->getMultiple('foo');
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "42"
	 */
	public function testGetMultipleInvalidKey(){
		$this->cache->getMultiple([42]);
	}

	public function testDeleteMultiple(){
		$this->cache->deleteMultiple(['k1', 'k3']);
		$this->assertFalse($this->cache->has('k1'));
		$this->assertFalse($this->cache->has('k3'));
		$this->assertTrue($this->cache->has('k2'));
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid data
	 */
	public function testDeleteMultipleInvalidData(){
		$this->cache->deleteMultiple('foo');
	}

	/**
	 * @expectedException \chillerlan\SimpleCache\InvalidArgumentException
	 * @expectedExceptionMessage invalid cache key: "42"
	 */
	public function testDeleteMultipleInvalidKey(){
		$this->cache->deleteMultiple([42]);
	}

	public function testClear(){
		$this->assertTrue($this->cache->has('42'));
		$this->assertTrue($this->cache->has('k2'));

		$this->assertTrue($this->cache->clear());

		$this->assertFalse($this->cache->has('42'));
		$this->assertFalse($this->cache->has('k2'));
	}

}
