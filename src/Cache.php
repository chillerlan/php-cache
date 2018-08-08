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
use Psr\Log\{LoggerAwareInterface, LoggerInterface, NullLogger};
use Psr\SimpleCache\CacheInterface;
use DateInterval, DateTime, Traversable;

class Cache implements CacheInterface, LoggerAwareInterface{

	/**
	 * The logger instance.
	 *
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var \chillerlan\SimpleCache\Drivers\CacheDriverInterface
	 */
	protected $cacheDriver;

	/**
	 * Cache constructor.
	 *
	 * @param \chillerlan\SimpleCache\Drivers\CacheDriverInterface $cacheDriver
	 * @param \Psr\Log\LoggerInterface                             $logger
	 */
	public function __construct(CacheDriverInterface $cacheDriver, LoggerInterface $logger = null){
		$this->cacheDriver = $cacheDriver;
		$this->setLogger($logger ?? new NullLogger);
	}

	/**
	 * Sets a logger.
	 *
	 * @param LoggerInterface $logger
	 *
	 * @return void
	 */
	public function setLogger(LoggerInterface $logger):void{
		$this->logger = $logger;
		$this->cacheDriver->setLogger($logger);
	}

	/** @inheritdoc */
	public function get($key, $default = null){
		$this->checkKey($key);

		return $this->cacheDriver->get($key, $default);
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		$this->checkKey($key);

		return $this->cacheDriver->set($key, $value, $this->getTTL($ttl));
	}

	/** @inheritdoc */
	public function delete($key):bool{
		$this->checkKey($key);

		return $this->cacheDriver->delete($key);
	}

	/** @inheritdoc */
	public function clear():bool{
		return $this->cacheDriver->clear();
	}

	/** @inheritdoc */
	public function getMultiple($keys, $default = null):iterable{
		$keys = $this->getData($keys);
		$this->checkKeyArray($keys);

		return $this->cacheDriver->getMultiple($keys, $default);
	}

	/** @inheritdoc */
	public function setMultiple($values, $ttl = null):bool{
		$values = $this->getData($values);

		foreach($values as $key => $value){
			$this->checkKey($key);
		}

		return $this->cacheDriver->setMultiple($values, $this->getTTL($ttl));
	}

	/** @inheritdoc */
	public function deleteMultiple($keys):bool{
		$keys = $this->getData($keys);
		$this->checkKeyArray($keys);

		return $this->cacheDriver->deleteMultiple($keys);
	}

	/** @inheritdoc */
	public function has($key):bool{
		$this->checkKey($key);

		return $this->cacheDriver->has($key);
	}

	/**
	 * @param $key
	 *
	 * @return void
	 * @throws \chillerlan\SimpleCache\InvalidArgumentException
	 */
	protected function checkKey($key):void{

		if(!is_string($key) || empty($key)){
			$msg = 'invalid cache key: "'.$key.'"';
			$this->logger->error($msg);

			throw new InvalidArgumentException($msg);
		}

	}

	/**
	 * @param array $keys
	 *
	 * @return void
	 */
	protected function checkKeyArray(array $keys):void{

		foreach($keys as $key){
			$this->checkKey($key);
		}

	}

	/**
	 * @param mixed $data
	 *
	 * @return array
	 * @throws \chillerlan\SimpleCache\InvalidArgumentException
	 */
	protected function getData($data):array{

		if($data instanceof Traversable){
			return iterator_to_array($data); // @codeCoverageIgnore
		}
		else if(is_array($data)){
			return $data;
		}

		$msg = 'invalid data';
		$this->logger->error($msg);

		throw new InvalidArgumentException($msg);
	}

	/**
	 * @param mixed $ttl
	 *
	 * @return int|null
	 * @throws \chillerlan\SimpleCache\InvalidArgumentException
	 */
	protected function getTTL($ttl):?int{

		if($ttl instanceof DateInterval){
			return (new DateTime('now'))->add($ttl)->getTimeStamp() - time();
		}
		else if(is_int($ttl) || $ttl === null){
			return $ttl;
		}

		$msg = 'invalid ttl';
		$this->logger->error($msg);

		throw new InvalidArgumentException($msg);
	}

	/**
	 * @param string $message
	 *
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	protected function throwException(string $message){
		$this->logger->error($message);

		throw new InvalidArgumentException($message);
	}

}
