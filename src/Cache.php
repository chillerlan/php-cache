<?php
/**
 * Class Cache
 *
 * @filesource   Cache.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use chillerlan\SimpleCache\Drivers\CacheDriverInterface;
use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface{

	/**
	 * @var \chillerlan\SimpleCache\Drivers\CacheDriverInterface
	 */
	protected $cacheDriver;

	/**
	 * Cache constructor.
	 *
	 * @param \chillerlan\SimpleCache\Drivers\CacheDriverInterface $cacheDriver
	 */
	public function __construct(CacheDriverInterface $cacheDriver){
		$this->cacheDriver = $cacheDriver;
	}

	/**
	 * Fetches a value from the cache.
	 *
	 * @param string $key     The unique key of this item in the cache.
	 * @param mixed  $default Default value to return if the key does not exist.
	 *
	 * @return mixed The value of the item from the cache, or $default in case of cache miss.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function get($key, $default = null){
		$this->checkKey($key);

		return $this->cacheDriver->get($key, $default);
	}

	/**
	 * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
	 *
	 * @param string                 $key   The key of the item to store.
	 * @param mixed                  $value The value of the item to store, must be serializable.
	 * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
	 *                                      the driver supports TTL then the library may set a default value
	 *                                      for it or let the driver take care of that.
	 *
	 * @return bool True on success and false on failure.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function set($key, $value, $ttl = null){
		$this->checkKey($key);

		return $this->cacheDriver->set($key, $value, $this->getTTL($ttl));
	}

	/**
	 * Delete an item from the cache by its unique key.
	 *
	 * @param string $key The unique cache key of the item to delete.
	 *
	 * @return bool True if the item was successfully removed. False if there was an error.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function delete($key){
		$this->checkKey($key);

		return $this->cacheDriver->delete($key);
	}

	/**
	 * Wipes clean the entire cache's keys.
	 *
	 * @return bool True on success and false on failure.
	 */
	public function clear(){
		return $this->cacheDriver->clear();
	}

	/**
	 * Obtains multiple cache items by their unique keys.
	 *
	 * @param iterable $keys    A list of keys that can obtained in a single operation.
	 * @param mixed    $default Default value to return for keys that do not exist.
	 *
	 * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as
	 *                  value.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if $keys is neither an array nor a Traversable,
	 *   or if any of the $keys are not a legal value.
	 */
	public function getMultiple($keys, $default = null){
		$keys = $this->getData($keys);
		$this->checkKeyArray($keys);

		return $this->cacheDriver->getMultiple($keys, $default);
	}

	/**
	 * Persists a set of key => value pairs in the cache, with an optional TTL.
	 *
	 * @param iterable               $values A list of key => value pairs for a multiple-set operation.
	 * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
	 *                                       the driver supports TTL then the library may set a default value
	 *                                       for it or let the driver take care of that.
	 *
	 * @return bool True on success and false on failure.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if $values is neither an array nor a Traversable,
	 *   or if any of the $values are not a legal value.
	 */
	public function setMultiple($values, $ttl = null){
		$values = $this->getData($values);

		foreach($values as $key => $value){
			$this->checkKey($key);
		}

		return $this->cacheDriver->setMultiple($values, $this->getTTL($ttl));
	}

	/**
	 * Deletes multiple cache items in a single operation.
	 *
	 * @param iterable $keys A list of string-based keys to be deleted.
	 *
	 * @return bool True if the items were successfully removed. False if there was an error.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if $keys is neither an array nor a Traversable,
	 *   or if any of the $keys are not a legal value.
	 */
	public function deleteMultiple($keys){
		$keys = $this->getData($keys);
		$this->checkKeyArray($keys);

		return $this->cacheDriver->deleteMultiple($keys);
	}

	/**
	 * Determines whether an item is present in the cache.
	 *
	 * NOTE: It is recommended that has() is only to be used for cache warming type purposes
	 * and not to be used within your live applications operations for get/set, as this method
	 * is subject to a race condition where your has() will return true and immediately after,
	 * another script can remove it making the state of your app out of date.
	 *
	 * @param string $key The cache item key.
	 *
	 * @return bool
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 *   MUST be thrown if the $key string is not a legal value.
	 */
	public function has($key){
		$this->checkKey($key);

		return $this->cacheDriver->has($key);
	}

	/**
	 * @param $key
	 *
	 * @return void
	 * @throws \chillerlan\SimpleCache\SimpleCacheException
	 */
	protected function checkKey($key){

		if(!is_string($key) || empty($key)){
			throw new SimpleCacheException('invalid key');
		}

	}

	/**
	 * @param array $keys
	 *
	 * @return void
	 * @throws \chillerlan\SimpleCache\SimpleCacheException
	 */
	protected function checkKeyArray(array $keys){

		foreach($keys as $key){
			$this->checkKey($key);
		}

	}

	/**
	 * @param mixed $data
	 *
	 * @return array
	 * @throws \chillerlan\SimpleCache\SimpleCacheException
	 */
	protected function getData($data):array{

		if($data instanceof \Traversable){
			return iterator_to_array($data); // @codeCoverageIgnore
		}
		else if(is_array($data)){
			return $data;
		}
		else{
			throw new SimpleCacheException('invalid data');
		}

	}

	/**
	 * @param mixed $ttl
	 *
	 * @return int|null
	 * @throws \chillerlan\SimpleCache\SimpleCacheException
	 */
	protected function getTTL($ttl){

		if($ttl instanceof \DateInterval){
			return $ttl->s;
		}
		else if(is_int($ttl) || is_null($ttl)){
			return $ttl;
		}
		else{
			throw new SimpleCacheException('invalid ttl');
		}

	}
}
