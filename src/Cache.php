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

	/** @inheritdoc */
	public function get($key, $default = null){
		$this->checkKey($key);

		return $this->cacheDriver->get($key, $default);
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null){
		$this->checkKey($key);

		return $this->cacheDriver->set($key, $value, $this->getTTL($ttl));
	}

	/** @inheritdoc */
	public function delete($key){
		$this->checkKey($key);

		return $this->cacheDriver->delete($key);
	}

	/** @inheritdoc */
	public function clear(){
		return $this->cacheDriver->clear();
	}

	/** @inheritdoc */
	public function getMultiple($keys, $default = null){
		$keys = $this->getData($keys);
		$this->checkKeyArray($keys);

		return $this->cacheDriver->getMultiple($keys, $default);
	}

	/** @inheritdoc */
	public function setMultiple($values, $ttl = null){
		$values = $this->getData($values);

		foreach($values as $key => $value){
			$this->checkKey($key);
		}

		return $this->cacheDriver->setMultiple($values, $this->getTTL($ttl));
	}

	/** @inheritdoc */
	public function deleteMultiple($keys){
		$keys = $this->getData($keys);
		$this->checkKeyArray($keys);

		return $this->cacheDriver->deleteMultiple($keys);
	}

	/** @inheritdoc */
	public function has($key){
		$this->checkKey($key);

		return $this->cacheDriver->has($key);
	}

	/**
	 * @param $key
	 *
	 * @return void
	 * @throws \chillerlan\SimpleCache\SimpleCacheInvalidArgumentException
	 */
	protected function checkKey($key){

		if(!is_string($key) || empty($key)){
			throw new SimpleCacheInvalidArgumentException('invalid key');
		}

	}

	/**
	 * @param array $keys
	 *
	 * @return void
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
	 * @throws \chillerlan\SimpleCache\SimpleCacheInvalidArgumentException
	 */
	protected function getData($data):array{

		if($data instanceof \Traversable){
			return iterator_to_array($data); // @codeCoverageIgnore
		}
		else if(is_array($data)){
			return $data;
		}

		throw new SimpleCacheInvalidArgumentException('invalid data');
	}

	/**
	 * @param mixed $ttl
	 *
	 * @return int|null
	 * @throws \chillerlan\SimpleCache\SimpleCacheInvalidArgumentException
	 */
	protected function getTTL($ttl){

		if($ttl instanceof \DateInterval){
			return (new \DateTime('now'))->add($ttl)->getTimeStamp() - time();
		}
		else if(is_int($ttl) || is_null($ttl)){
			return $ttl;
		}

		throw new SimpleCacheInvalidArgumentException('invalid ttl');
	}

}
