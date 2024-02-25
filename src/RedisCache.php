<?php
/**
 * Class RedisCache
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 * @phan-file-suppress PhanUndeclaredClassMethod, PhanUndeclaredTypeProperty, PhanUndeclaredTypeParameter
 */

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\{LoggerInterface, NullLogger};
use DateInterval, Redis;

use function array_combine, array_keys, extension_loaded;

/**
 * Implements a cache via Redis
 *
 * Note: this implementation ignores "multimode" entirely, which would return a Redis instance for every operation
 *
 * @see https://github.com/phpredis/phpredis/
 */
class RedisCache extends CacheDriverAbstract{

	protected Redis $redis;

	/**
	 * RedisCache constructor.
	 *
	 * @throws \Psr\SimpleCache\CacheException
	 */
	public function __construct(
		Redis $redis,
		SettingsContainerInterface|CacheOptions $options = new CacheOptions,
		LoggerInterface $logger = new NullLogger
	){

		if(!extension_loaded('redis')){
			throw new CacheException('Redis not installed/enabled');
		}

		parent::__construct($options, $logger);

		$this->redis = $redis;
	}

	/** @inheritdoc */
	public function get(string $key, mixed $default = null):mixed{
		$value = $this->redis->get($this->checkKey($key));

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/** @inheritdoc */
	public function set(string $key, mixed $value, int|DateInterval|null $ttl = null):bool{
		$key = $this->checkKey($key);
		$ttl = $this->getTTL($ttl);

		if($ttl === null){
			return $this->redis->set($key, $value);
		}

		return $this->redis->setex($key, $ttl, $value);
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		return (bool)$this->redis->del($this->checkKey($key));;
	}

	/** @inheritdoc */
	public function clear():bool{
		return $this->redis->flushDB();
	}

	/** @inheritdoc */
	public function getMultiple(iterable $keys, mixed $default = null):iterable{
		$keys = $this->checkKeyArray($this->fromIterable($keys));

		// scary
		$values = array_combine($keys, $this->redis->mget($keys));
		$return = [];

		foreach($keys as $key){
			/** @phan-suppress-next-line PhanTypeArraySuspiciousNullable */
			$return[$key] = ($values[$key] !== false) ? $values[$key] : $default;
		}

		return $return;
	}

	/** @inheritdoc */
	public function setMultiple(iterable $values, int|DateInterval|null $ttl = null):bool{
		$values = $this->fromIterable($values);
		$ttl    = $this->getTTL($ttl);

		if($ttl === null){
			$this->checkKeyArray(array_keys($values));

			return $this->redis->msetnx($values);
		}

		$return = [];

		foreach($values as $key => $value){
			$return[] = $this->set($key, $value, $ttl);
		}

		return $this->checkReturn($return);
	}

	/** @inheritdoc */
	public function deleteMultiple(iterable $keys):bool{
		$keys = $this->checkKeyArray($this->fromIterable($keys));

		return (bool)$this->redis->del($keys);
	}

}
