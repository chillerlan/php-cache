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

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\LoggerInterface;
use Redis;

use function array_combine, array_keys;

class RedisCache extends CacheDriverAbstract{

	protected Redis $redis;

	/**
	 * RedisCache constructor.
	 */
	public function __construct(Redis $redis, SettingsContainerInterface $options = null, LoggerInterface $logger = null){
		parent::__construct($options, $logger);

		$this->redis = $redis;
	}

	/** @inheritdoc */
	public function get($key, $default = null){
		$value = $this->redis->get($this->checkKey($key));

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		$key = $this->checkKey($key);
		$ttl = $this->getTTL($ttl);

		if($ttl === null){
			return $this->redis->set($key, $value);
		}

		return $this->redis->setex($key, $ttl, $value);
	}

	/** @inheritdoc */
	public function delete($key):bool{
		return (bool)$this->redis->del($this->checkKey($key));
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
			/** @phan-suppress-next-line PhanTypeArraySuspiciousNullable */
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

		return (bool)$this->redis->del($keys);
	}

}
