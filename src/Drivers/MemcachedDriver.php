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

use chillerlan\SimpleCache\CacheException;
use chillerlan\Traits\ImmutableSettingsInterface;
use Memcached;

class MemcachedDriver extends CacheDriverAbstract{

	/**
	 * @var \Memcached
	 */
	protected $memcached;

	/**
	 * MemcachedDriver constructor.
	 *
	 * @param \Memcached                                         $memcached
	 * @param \chillerlan\Traits\ImmutableSettingsInterface|null $options
	 *
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	public function __construct(Memcached $memcached, ImmutableSettingsInterface $options = null){
		parent::__construct($options);

		$this->memcached = $memcached;

		if(empty($this->memcached->getServerList())){
			$msg = 'no memcache server available';

			$this->logger->error($msg);
			throw new CacheException($msg);
		}

	}

	/** @inheritdoc */
	public function get(string $key, $default = null){
		$value = $this->memcached->get($key);

		return $value ?: $default;
	}

	/** @inheritdoc */
	public function set(string $key, $value, int $ttl = null):bool{
		return $this->memcached->set($key, $value, $ttl);
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		return $this->memcached->delete($key);
	}

	/** @inheritdoc */
	public function clear():bool{
		return $this->memcached->flush();
	}

	/** @inheritdoc */
	public function getMultiple(array $keys, $default = null):array{
		$values = $this->memcached->getMulti($keys);
		$return = [];

		foreach($keys as $key){
			$return[$key] = $values[$key] ?? $default;
		}

		return $return;
	}

	/** @inheritdoc */
	public function setMultiple(array $values, int $ttl = null):bool{
		return $this->memcached->setMulti($values, $ttl);
	}

	/** @inheritdoc */
	public function deleteMultiple(array $keys):bool{
		$return = $this->memcached->deleteMulti($keys);

		return $this->checkReturn((array)$return);
	}

}
