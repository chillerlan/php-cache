<?php
/**
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

/**
 * Class RedisDriver
 */
class RedisDriver extends CacheDriverAbstract{

	/**
	 * @var \Redis
	 */
	protected $redis;

	public function __construct(Redis $redis){
		$this->redis = $redis;
	}

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null){
		$value = $this->redis->get($key);

		return $value ? $value : $default;
	}

	/**
	 * @param string   $key
	 * @param          $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function set(string $key, $value, int $ttl = null):bool{

		if(is_null($ttl)){
			return $this->redis->set($key, $value);
		}

		return $this->redis->setex($key, $ttl, $value);
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete(string $key):bool{
		return (bool)$this->redis->delete($key);
	}

	/**
	 * @return bool
	 */
	public function clear():bool{
		return $this->redis->flushDB();
	}

	/**
	 * @param array $keys
	 * @param null  $default
	 *
	 * @return array
	 */
	public function getMultiple(array $keys, $default = null):array{
		// scary
		$values = array_combine($keys, $this->redis->mget($keys));

		$return = [];

		foreach($keys as $key){
			$return[$key] = $values[$key] !== false ? $values[$key] : $default;
		}

		return $return;
	}

	/**
	 * @param array    $values
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
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

	/**
	 * @param array $keys
	 *
	 * @return bool
	 */
	public function deleteMultiple(array $keys):bool{
		return (bool)$this->redis->delete($keys);
	}
}
