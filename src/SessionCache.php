<?php
/**
 * Class SessionCache
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use Psr\Log\LoggerInterface;

use function time;

class SessionCache extends CacheDriverAbstract{

	protected string $key;

	/**
	 * SessionCache constructor.
	 *
	 * @throws \Psr\SimpleCache\CacheException
	 */
	public function __construct(SettingsContainerInterface $options = null, LoggerInterface $logger = null){
		parent::__construct($options, $logger);

		$this->key = $this->options->cacheSessionkey;

		if(!is_string($this->key) || empty($this->key)){
			throw new CacheException('invalid session cache key');
		}


		$_SESSION[$this->key] = [];
	}

	/** @inheritdoc */
	public function get($key, $default = null){
		$key = $this->checkKey($key);

		if(isset($_SESSION[$this->key][$key])){

			if($_SESSION[$this->key][$key]['ttl'] === null || $_SESSION[$this->key][$key]['ttl'] > time()){
				return $_SESSION[$this->key][$key]['content'];
			}

			unset($_SESSION[$this->key][$key]);
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		$ttl = $this->getTTL($ttl);

		$_SESSION[$this->key][$this->checkKey($key)] = [
			'ttl'     => $ttl ? time() + $ttl : null,
			'content' => $value,
		];

		return true;
	}

	/** @inheritdoc */
	public function delete($key):bool{
		unset($_SESSION[$this->key][$this->checkKey($key)]);

		return true;
	}

	/** @inheritdoc */
	public function clear():bool{
		$_SESSION[$this->key] = [];

		return true;
	}

}
