<?php
/**
 * Class MemcachedDriver
 *
 * @filesource   MemcachedDriver.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

use chillerlan\SimpleCache\SimpleCacheException;
use Memcached;

class MemcachedDriver extends CacheDriverAbstract{

	/**
	 * @var \Memcached
	 */
	protected $memcached;

	/**
	 * MemcachedDriver constructor.
	 *
	 * @param \Memcached $memcached
	 *
	 * @throws \chillerlan\SimpleCache\SimpleCacheException
	 */
	public function __construct(Memcached $memcached){
		$this->memcached = $memcached;

		if(empty($this->memcached->getServerList())){
			throw new SimpleCacheException('no memcache server available');
		}

	}

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null){
		$value = $this->memcached->get($key);

		if($value){
			return $value;
		}

		return $default;
	}

	/**
	 * @param string   $key
	 * @param          $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function set(string $key, $value, int $ttl = null):bool{
		return $this->memcached->set($key, $value, $ttl);
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete(string $key):bool{
		return $this->memcached->delete($key);
	}

	/**
	 * @return bool
	 */
	public function clear():bool{
		return $this->memcached->flush();
	}

	/**
	 * @param array $keys
	 * @param null  $default
	 *
	 * @return array
	 */
	public function getMultiple(array $keys, $default = null):array{
		$values = $this->memcached->getMulti($keys);
		$return = [];

		foreach($keys as $key){
			$return[$key] = $values[$key] ?? $default;
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
		return $this->memcached->setMulti($values, $ttl);
	}

	/**
	 * @param array $keys
	 *
	 * @return bool
	 */
	public function deleteMultiple(array $keys):bool{
		$return = $this->memcached->deleteMulti($keys);

		/** @var bool[] $return */
		return $this->checkReturn($return);
	}

}
