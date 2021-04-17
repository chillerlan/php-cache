<?php
/**
 * Class MemcachedCache
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 * @phan-file-suppress PhanUndeclaredClassMethod, PhanUndeclaredTypeProperty, PhanUndeclaredTypeParameter
 */

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Memcached;
use Psr\Log\LoggerInterface;

use function array_keys;

class MemcachedCache extends CacheDriverAbstract{

	protected Memcached $memcached;

	/**
	 * MemcachedCache constructor.
	 *
	 * @param \Memcached                                           $memcached
	 * @param \chillerlan\Settings\SettingsContainerInterface|null $options
	 * @param \Psr\Log\LoggerInterface|null                        $logger
	 *
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	public function __construct(Memcached $memcached, SettingsContainerInterface $options = null, LoggerInterface $logger = null){
		parent::__construct($options, $logger);

		$this->memcached = $memcached;

		if(empty($this->memcached->getServerList())){
			throw new CacheException('no memcache server available');
		}

	}

	/** @inheritdoc */
	public function get($key, $default = null){
		$value = $this->memcached->get($this->checkKey($key));

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		return $this->memcached->set($this->checkKey($key), $value, $this->getTTL($ttl) ?? 0);
	}

	/** @inheritdoc */
	public function delete($key):bool{
		return $this->memcached->delete($this->checkKey($key));
	}

	/** @inheritdoc */
	public function clear():bool{
		return $this->memcached->flush();
	}

	/** @inheritdoc */
	public function getMultiple($keys, $default = null):array{
		$keys = $this->getData($keys);

		$this->checkKeyArray($keys);

		$values = $this->memcached->getMulti($keys);
		$return = [];

		foreach($keys as $key){
			$return[$key] = $values[$key] ?? $default;
		}

		return $return;
	}

	/** @inheritdoc */
	public function setMultiple($values, $ttl = null):bool{
		$values = $this->getData($values);

		$this->checkKeyArray(array_keys($values));

		return $this->memcached->setMulti($values, $this->getTTL($ttl) ?? 0);
	}

	/** @inheritdoc */
	public function deleteMultiple($keys):bool{
		$keys = $this->getData($keys);

		$this->checkKeyArray($keys);

		return $this->checkReturn($this->memcached->deleteMulti($keys));
	}

}
