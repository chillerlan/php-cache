<?php
/**
 * Class RedisCache
 *
 * @filesource   RedisCache.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Redis;

class RedisCache extends CacheDriverAbstract{

	/**
	 * @var \Redis
	 */
	protected $redis;

	/**
	 * RedisCache constructor.
	 *
	 * @param \Redis                                             $redis
	 * @param \chillerlan\Settings\SettingsContainerInterface|null $options
	 */
	public function __construct(Redis $redis, SettingsContainerInterface $options = null){
		parent::__construct($options);

		$this->redis = $redis;
	}

	/** @inheritdoc */
	public function get($key, $default = null){
		$this->checkKey($key);

		$value = $this->redis->get($key);

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		$this->checkKey($key);

		$ttl = $this->getTTL($ttl);

		if($ttl === null){
			return $this->redis->set($key, $value);
		}

		return $this->redis->setex($key, $ttl, $value);
	}

	/** @inheritdoc */
	public function delete($key):bool{
		$this->checkKey($key);

		return (bool)$this->redis->delete($key);
	}

	/** @inheritdoc */
	public function clear():bool{
		return $this->redis->flushDB();
	}

	/** @inheritdoc */
	public function getMultiple($keys, $default = null):array{
		$keys = $this->getData($keys);

		$this->checkKeyArray($keys);

		// scary
		$values = array_combine($keys, $this->redis->mget($keys));

		$return = [];

		foreach($keys as $key){
			$return[$key] = $values[$key] !== false ? $values[$key] : $default;
		}

		return $return;
	}

	/** @inheritdoc */
	public function setMultiple($values, $ttl = null):bool{
		$values = $this->getData($values);
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
	public function deleteMultiple($keys):bool{
		$keys = $this->getData($keys);

		$this->checkKeyArray($keys);

		return (bool)$this->redis->delete($keys);
	}

}
