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

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

abstract class SimpleCacheTestAbstract extends TestCase{

	/**
	 * @var \chillerlan\SimpleCache\CacheDriverInterface
	 */
	protected $cacheDriver;

	/**
	 * @var \chillerlan\SimpleCache\Cache
	 */
	protected $cache;

#	protected function setUp():void{
#	}

	public function testInstance(){
#		$this->assertInstanceOf(CacheDriverInterface::class, $this->cacheDriver);
		$this->assertInstanceOf(CacheInterface::class, $this->cache);
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

	public function testSetInvalidTTL(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid ttl');

		$this->cache->set('.', '', new \stdClass);
	}

	public function testSetInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->assertTrue($this->cache->set(42, 'nope'));
	}

	public function testGet(){
		$this->assertSame('whatever', $this->cache->get('hello'));
		$this->assertNull($this->cache->get('foo'));
		$this->assertSame('default', $this->cache->get('foo', 'default'));
	}

	public function testGetInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->get(42);
	}

	public function testHas(){
		$this->assertTrue($this->cache->has('hello'));
	}

	public function testHasInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->has(42);
	}

	public function testDelete(){
		$this->assertTrue($this->cache->delete('hello'));
		$this->assertFalse($this->cache->delete('hello'));
	}

	public function testDeleteInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

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

	public function testSetMultipleInvalidTTL(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid ttl');

		$this->cache->setMultiple(['.' => ''], new \stdClass);
	}

	public function testSetMultipleInvalidData(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid data');

		$this->cache->setMultiple('foo');
	}

	public function testSetMultipleInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "0"');

		$this->cache->setMultiple(['foo']);
	}

	public function testGetMultiple(){
		$this->assertSame(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3'], $this->cache->getMultiple(['k1', 'k2', 'k3']));
	}

	public function testGetMultipleInvalidData(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid data');

		$this->cache->getMultiple('foo');
	}

	public function testGetMultipleInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->getMultiple([42]);
	}

	public function testDeleteMultiple(){
		$this->cache->deleteMultiple(['k1', 'k3']);
		$this->assertFalse($this->cache->has('k1'));
		$this->assertFalse($this->cache->has('k3'));
		$this->assertTrue($this->cache->has('k2'));
	}

	public function testDeleteMultipleInvalidData(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid data');

		$this->cache->deleteMultiple('foo');
	}

	public function testDeleteMultipleInvalidKey(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

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
