<?php
/**
 * Class RedisDriver
 *
 * @filesource   RedisDriver.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

use Redis;

class RedisDriver extends CacheDriverAbstract{

	/**
	 * @var \Redis
	 */
	protected $redis;

	/**
	 * RedisDriver constructor.
	 *
	 * @param \Redis $redis
	 */
	public function __construct(Redis $redis){
		$this->redis = $redis;
	}

	/** @inheritdoc */
	public function get(string $key, $default = null){
		$value = $this->redis->get($key);

		return $value ? $value : $default;
	}

	/** @inheritdoc */
	public function set(string $key, $value, int $ttl = null):bool{

		if(is_null($ttl)){
			return $this->redis->set($key, $value);
		}

		return $this->redis->setex($key, $ttl, $value);
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		return (bool)$this->redis->delete($key);
	}

	/** @inheritdoc */
	public function clear():bool{
		return $this->redis->flushDB();
	}

	/** @inheritdoc */
	public function getMultiple(array $keys, $default = null):array{
		// scary
		$values = array_combine($keys, $this->redis->mget($keys));

		$return = [];

		foreach($keys as $key){
			$return[$key] = $values[$key] !== false ? $values[$key] : $default;
		}

		return $return;
	}

	/** @inheritdoc */
	public function setMultiple(array $values, int $ttl = null):bool{

		if(is_null($ttl)){
			return $this->redis->msetnx($values);
		}

		$return = [];

		foreach($values as $key => $value){
			$return[] = $this->set($key, $value, $ttl);
		}

		return $this->checkReturn($return);
	}

	/** @inheritdoc */
	public function deleteMultiple(array $keys):bool{
		return (bool)$this->redis->delete($keys);
	}
}
