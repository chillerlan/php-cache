<?php
/**
 * Class SessionCacheDriver
 *
 * @filesource   SessionCacheDriver.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

use chillerlan\SimpleCache\CacheException;
use chillerlan\Settings\SettingsContainerInterface;

class SessionCacheDriver extends CacheDriverAbstract{

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * SessionCacheDriver constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface|null $options
	 *
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	public function __construct(SettingsContainerInterface $options = null){
		parent::__construct($options);

		$this->key = $this->options->cacheSessionkey;

		if(!is_string($this->key) || empty($this->key)){
			$msg = 'invalid session cache key';

			$this->logger->error($msg);
			throw new CacheException($msg);
		}


		$_SESSION[$this->key] = [];
	}

	/** @inheritdoc */
	public function get(string $key, $default = null){

		if(isset($_SESSION[$this->key][$key])){

			if($_SESSION[$this->key][$key]['ttl'] === null || $_SESSION[$this->key][$key]['ttl'] > time()){
				return $_SESSION[$this->key][$key]['content'];
			}

			unset($_SESSION[$this->key][$key]);

		}

		return $default;
	}

	/** @inheritdoc */
	public function set(string $key, $value, int $ttl = null):bool{

		$_SESSION[$this->key][$key] = [
			'ttl' => $ttl ? time() + $ttl : null,
			'content' => $value,
		];

		return true;
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		unset($_SESSION[$this->key][$key]);

		return true;
	}

	/** @inheritdoc */
	public function clear():bool{
		$_SESSION[$this->key] = [];

		return true;
	}

}
