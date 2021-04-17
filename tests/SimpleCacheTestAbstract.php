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
 *
 * @phan-file-suppress PhanTypeMismatchArgument
 */

namespace chillerlan\SimpleCacheTest;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\{CacheInterface, InvalidArgumentException};
use DateInterval, stdClass;

use function sleep;

abstract class SimpleCacheTestAbstract extends TestCase{

	protected CacheInterface $cache;

	public function testInstance(){
		self::assertInstanceOf(CacheInterface::class, $this->cache);
	}

	public function testSet(){
		self::assertTrue($this->cache->set('hello', 'whatever'));
		self::assertTrue($this->cache->set('42', 'yep'));
	}

	public function testSetTTL(){
		self::assertTrue($this->cache->set('what', 'nope', new DateInterval('PT2S')));
		self::assertTrue($this->cache->set('oh', 'wait', 2));
		self::assertSame('nope', $this->cache->get('what'));
		self::assertSame('wait', $this->cache->get('oh'));

		sleep(3);

		self::assertFalse($this->cache->has('what'));
		self::assertFalse($this->cache->has('oh'));
	}

	public function testSetInvalidTTLException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid ttl');

		$this->cache->set('.', '', new stdClass);
	}

	public function testSetInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		self::assertTrue($this->cache->set(42, 'nope'));
	}

	public function testGet(){
		self::assertSame('whatever', $this->cache->get('hello'));
		self::assertNull($this->cache->get('foo'));
		self::assertSame('default', $this->cache->get('foo', 'default'));
	}

	public function testGetInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->get(42);
	}

	public function testHas(){
		self::assertTrue($this->cache->has('hello'));
	}

	public function testHasInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->has(42);
	}

	public function testDelete(){
		self::assertTrue($this->cache->delete('hello'));
		self::assertFalse($this->cache->delete('hello'));
	}

	public function testDeleteInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->delete(42);
	}

	public function testSetMultiple(){
		self::assertTrue($this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']));
	}

	public function testSetMultipleTTL(){
		self::assertTrue($this->cache->setMultiple(['k1ttl' => 'v1ttl'], new DateInterval('PT2S')));
		self::assertTrue($this->cache->setMultiple(['k2ttl' => 'v2ttl'], 2));
		self::assertSame(['k1ttl' => 'v1ttl', 'k2ttl' => 'v2ttl'], $this->cache->getMultiple(['k1ttl', 'k2ttl']));

		sleep(3);

		self::assertSame(['k1ttl' => null, 'k2ttl' => null], $this->cache->getMultiple(['k1ttl', 'k2ttl']));
	}

	public function testSetMultipleInvalidTTLException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid ttl');

		$this->cache->setMultiple(['.' => ''], new stdClass);
	}

	public function testSetMultipleInvalidDataException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid data');

		$this->cache->setMultiple('foo');
	}

	public function testSetMultipleInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "0"');

		$this->cache->setMultiple(['foo']);
	}

	public function testGetMultiple(){
		self::assertSame(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3'], $this->cache->getMultiple(['k1', 'k2', 'k3']));
	}

	public function testGetMultipleInvalidDataException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid data');

		$this->cache->getMultiple('foo');
	}

	public function testGetMultipleInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->getMultiple([42]);
	}

	public function testDeleteMultiple(){
		$this->cache->deleteMultiple(['k1', 'k3']);
		self::assertFalse($this->cache->has('k1'));
		self::assertFalse($this->cache->has('k3'));
		self::assertTrue($this->cache->has('k2'));
	}

	public function testDeleteMultipleInvalidDataException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid data');

		$this->cache->deleteMultiple('foo');
	}

	public function testDeleteMultipleInvalidKeyException(){
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid cache key: "42"');

		$this->cache->deleteMultiple([42]);
	}

	public function testClear(){
		self::assertTrue($this->cache->has('42'));
		self::assertTrue($this->cache->has('k2'));

		self::assertTrue($this->cache->clear());

		self::assertFalse($this->cache->has('42'));
		self::assertFalse($this->cache->has('k2'));
	}

}
