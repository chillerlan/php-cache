<?php
/**
 * Class CacheDriverAbstract
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @phan-file-suppress PhanTypeInvalidThrowsIsInterface
 */

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait, LoggerInterface, NullLogger};
use Psr\SimpleCache\CacheInterface;
use DateInterval, DateTime, InvalidArgumentException, Traversable;
use function is_array, is_int, iterator_to_array, time;

abstract class CacheDriverAbstract implements CacheInterface, LoggerAwareInterface{
	use LoggerAwareTrait;

	protected SettingsContainerInterface|CacheOptions $options;

	/**
	 * CacheDriverAbstract constructor.
	 */
	public function __construct(
		SettingsContainerInterface|CacheOptions $options = new CacheOptions,
		LoggerInterface $logger = new NullLogger
	){
		$this->options = $options;
		$this->logger  = $logger;
	}

	/** @inheritdoc */
	public function has(string $key):bool{
		return $this->get($key) !== null;
	}

	/** @inheritdoc */
	public function getMultiple(iterable $keys, mixed $default = null):iterable{
		$data = [];

		foreach($this->fromIterable($keys) as $key){
			$data[$key] = $this->get($key, $default);
		}

		return $data;
	}

	/** @inheritdoc */
	public function setMultiple(iterable $values, int|DateInterval|null $ttl = null):bool{
		$return = [];

		foreach($this->fromIterable($values) as $key => $value){
			$return[] = $this->set($key, $value, $ttl);
		}

		return $this->checkReturn($return);
	}

	/** @inheritdoc */
	public function deleteMultiple(iterable $keys):bool{
		$return = [];

		foreach($this->fromIterable($keys) as $key){
			$return[] = $this->delete($key);
		}

		return $this->checkReturn($return);
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	protected function checkKey(string $key):string{

		if(empty($key)){
			throw new InvalidArgumentException('cache key is empty');
		}

		return $key;
	}

	/**  */
	protected function checkKeyArray(array $keys):array{

		foreach($keys as $key){
			$this->checkKey($key);
		}

		return $keys;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	protected function fromIterable(iterable $data):array{

		if(is_array($data)){
			return $data;
		}

		if($data instanceof Traversable){
			return iterator_to_array($data); // @codeCoverageIgnore
		}

		throw new InvalidArgumentException('invalid data');
	}

	/**  */
	protected function getTTL(DateInterval|int|null $ttl):?int{

		if($ttl instanceof DateInterval){
			return ((new DateTime)->add($ttl)->getTimeStamp() - time());
		}

		if((is_int($ttl) && $ttl > 0)){
			return $ttl;
		}

		return null;
	}

	/**
	 * @param bool[]|int[] $booleans
	 */
	protected function checkReturn(array $booleans):bool{

		foreach($booleans as $boolean){

			if(!(bool)$boolean){
				return false; // @codeCoverageIgnore
			}

		}

		return true;
	}

}
