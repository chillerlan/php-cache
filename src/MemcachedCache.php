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

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Memcached;
use Psr\Log\{LoggerInterface, NullLogger};
use function array_keys, extension_loaded;

/**
 * Implements a cache via the Memcached extension
 *
 * @see https://www.php.net/manual/en/book.memcached.php
 */
class MemcachedCache extends CacheDriverAbstract{

	protected Memcached $memcached;

	/**
	 * MemcachedCache constructor.
	 *
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	public function __construct(
		Memcached $memcached,
		SettingsContainerInterface|CacheOptions $options = new CacheOptions,
		LoggerInterface $logger = new NullLogger
	){

		if(!extension_loaded('memcached')){
			throw new CacheException('Memcached not installed/enabled');
		}

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
		return $this->memcached->set($this->checkKey($key), $value, ($this->getTTL($ttl) ?? 0));
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
		$keys   = $this->checkKeyArray($this->fromIterable($keys));
		$values = $this->memcached->getMulti($keys);
		$return = [];

		foreach($keys as $key){
			$return[$key] = ($values[$key] ?? $default);
		}

		return $return;
	}

	/** @inheritdoc */
	public function setMultiple($values, $ttl = null):bool{
		$values = $this->fromIterable($values);

		$this->checkKeyArray(array_keys($values));

		return $this->memcached->setMulti($values, ($this->getTTL($ttl) ?? 0));
	}

	/** @inheritdoc */
	public function deleteMultiple($keys):bool{
		$keys = $this->checkKeyArray($this->fromIterable($keys));

		return $this->checkReturn($this->memcached->deleteMulti($keys));
	}

}
