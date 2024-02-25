<?php
/**
 * Class SessionCache
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\{LoggerInterface, NullLogger};
use DateInterval;

use function time;

/**
 * Implements a cache via PHP sessions
 */
class SessionCache extends CacheDriverAbstract{

	protected string $name;

	/**
	 * SessionCache constructor.
	 *
	 * @throws \Psr\SimpleCache\CacheException
	 */
	public function __construct(
		SettingsContainerInterface|CacheOptions $options = new CacheOptions,
		LoggerInterface $logger = new NullLogger
	){
		parent::__construct($options, $logger);

		$this->name = $this->options->cacheSessionkey;

		if(empty($this->name)){
			throw new CacheException('invalid session cache key');
		}

		$this->clear();
	}

	/** @inheritdoc */
	public function get(string $key, mixed $default = null):mixed{
		$key = $this->checkKey($key);

		if(isset($_SESSION[$this->name][$key])){

			if($_SESSION[$this->name][$key]['ttl'] === null || $_SESSION[$this->name][$key]['ttl'] > time()){
				return $_SESSION[$this->name][$key]['content'];
			}

			unset($_SESSION[$this->name][$key]);
		}

		return $default;
	}

	/** @inheritdoc */
	public function set(string $key, mixed $value, int|DateInterval|null $ttl = null):bool{
		$ttl = $this->getTTL($ttl);

		if($ttl !== null){
			$ttl = (time() + $ttl);
		}

		$_SESSION[$this->name][$this->checkKey($key)] = ['ttl' => $ttl, 'content' => $value];

		return true;
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		unset($_SESSION[$this->name][$this->checkKey($key)]);

		return true;
	}

	/** @inheritdoc */
	public function clear():bool{
		$_SESSION[$this->name] = [];

		return true;
	}

}
