<?php
/**
 * Class SimpleCacheTestAbstract
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @phan-file-suppress PhanTypeMismatchArgument, PhanTypeMismatchArgumentReal, PhanTypeMismatchArgumentProbablyReal
 */

declare(strict_types=1);

namespace chillerlan\SimpleCacheTest;

use chillerlan\Settings\SettingsContainerInterface;
use chillerlan\SimpleCache\CacheOptions;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use DateInterval, InvalidArgumentException, TypeError;
use function sleep;

abstract class SimpleCacheTestAbstract extends TestCase{

	protected CacheInterface $cache;
	protected SettingsContainerInterface $options;

	protected function setUp():void{
		$this->options = new CacheOptions;
	}

	public function testInstance():void{
		$this::assertInstanceOf(CacheInterface::class, $this->cache);
	}

	public function testSet():void{
		$this::assertTrue($this->cache->set('hello', 'whatever'));
		$this::assertTrue($this->cache->set('42', 'yep'));
	}

	public function testSetTTL():void{
		$this::assertTrue($this->cache->set('what', 'nope', new DateInterval('PT2S')));
		$this::assertTrue($this->cache->set('oh', 'wait', 2));
		$this::assertSame('nope', $this->cache->get('what'));
		$this::assertSame('wait', $this->cache->get('oh'));

		sleep(3);

		$this::assertFalse($this->cache->has('what'));
		$this::assertFalse($this->cache->has('oh'));
	}

	public function testSetInvalidTTLException():void{
		$this->expectException(TypeError::class);

		$this->cache->set('.', '', []);
	}

	public function testSetInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this::assertTrue($this->cache->set(42, 'nope'));
	}

	public function testGet():void{
		$this::assertSame('whatever', $this->cache->get('hello'));
		$this::assertNull($this->cache->get('foo'));
		$this::assertSame('default', $this->cache->get('foo', 'default'));
	}

	public function testGetEmptyKeyException():void{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('cache key is empty');

		$this->cache->get('');
	}

	public function testGetInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this->cache->get(42);
	}

	public function testHas():void{
		$this::assertTrue($this->cache->has('hello'));
	}

	public function testHasInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this->cache->has(42);
	}

	public function testDelete():void{
		$this::assertTrue($this->cache->delete('hello'));
		$this::assertFalse($this->cache->delete('hello'));
	}

	public function testDeleteInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this->cache->delete(42);
	}

	public function testSetMultiple():void{
		$this::assertTrue($this->cache->setMultiple(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']));
	}

	public function testSetMultipleTTL():void{
		$this::assertTrue($this->cache->setMultiple(['k1ttl' => 'v1ttl'], new DateInterval('PT2S')));
		$this::assertTrue($this->cache->setMultiple(['k2ttl' => 'v2ttl'], 2));
		$this::assertSame(['k1ttl' => 'v1ttl', 'k2ttl' => 'v2ttl'], $this->cache->getMultiple(['k1ttl', 'k2ttl']));

		sleep(3);

		$this::assertSame(['k1ttl' => null, 'k2ttl' => null], $this->cache->getMultiple(['k1ttl', 'k2ttl']));
	}

	public function testSetMultipleInvalidTTLException():void{
		$this->expectException(TypeError::class);

		$this->cache->setMultiple(['.' => ''], []);
	}

	public function testSetMultipleInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this->cache->setMultiple([0 => 'foo']);
	}

	public function testGetMultiple():void{
		$this::assertSame(['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3'], $this->cache->getMultiple(['k1', 'k2', 'k3']));
	}

	public function testGetMultipleInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this->cache->getMultiple([42]);
	}

	public function testDeleteMultiple():void{
		$this->cache->deleteMultiple(['k1', 'k3']);

		$this::assertFalse($this->cache->has('k1'));
		$this::assertFalse($this->cache->has('k3'));
		$this::assertTrue($this->cache->has('k2'));
	}

	public function testDeleteMultipleInvalidKeyException():void{
		$this->expectException(TypeError::class);

		$this->cache->deleteMultiple([42]);
	}

	public function testClear():void{
		$this::assertTrue($this->cache->has('42'));
		$this::assertTrue($this->cache->has('k2'));

		$this::assertTrue($this->cache->clear());

		$this::assertFalse($this->cache->has('42'));
		$this::assertFalse($this->cache->has('k2'));
	}

}
