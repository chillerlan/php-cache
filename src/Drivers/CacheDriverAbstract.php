<?php
/**
 * Class CacheDriverAbstract
 *
 * @filesource   CacheDriverAbstract.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

use chillerlan\SimpleCache\{CacheOptions};
use chillerlan\Traits\ImmutableSettingsInterface;
use Psr\Log\{LoggerAwareInterface, LoggerAwareTrait, NullLogger};

abstract class CacheDriverAbstract implements CacheDriverInterface, LoggerAwareInterface{
	use LoggerAwareTrait;

	/**
	 * @var \chillerlan\SimpleCache\CacheOptions
	 */
	protected $options;

	/**
	 * CacheDriverAbstract constructor.
	 *
	 * @param \chillerlan\Traits\ImmutableSettingsInterface|null $options
	 */
	public function __construct(ImmutableSettingsInterface $options = null){
		$this->options = $options ?? new CacheOptions;
		$this->logger  = new NullLogger; // logger will be set from the Cache instance
	}

	/** @inheritdoc */
	public function has(string $key):bool{
		return (bool)$this->get($key);
	}

	/** @inheritdoc */
	public function getMultiple(array $keys, $default = null):array{
		$data = [];

		foreach($keys as $key){
			$data[$key] = $this->get($key, $default);
		}

		return $data;
	}

	/** @inheritdoc */
	public function setMultiple(array $values, int $ttl = null):bool{
		$return = [];

		foreach($values as $key => $value){
			$return[] = $this->set($key, $value, $ttl);
		}

		return $this->checkReturn($return);
	}

	/** @inheritdoc */
	public function deleteMultiple(array $keys):bool{
		$return = [];

		foreach($keys as $key){
			$return[] = $this->delete($key);
		}

		return $this->checkReturn($return);
	}

	/**
	 * @param bool[] $booleans
	 *
	 * @return bool
	 */
	protected function checkReturn(array $booleans):bool{

		foreach($booleans as $bool){

			if(!(bool)$bool){
				return false; // @codeCoverageIgnore
			}

		}

		return true;
	}

}
