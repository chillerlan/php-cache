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

class SessionCacheDriver extends CacheDriverAbstract{

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * SessionCacheDriver constructor.
	 *
	 * @param string|null $key
	 */
	public function __construct(string $key = null){
		$this->key = $key ?? '_session_cache';

		$_SESSION[$this->key] = [];
	}

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null){

		if(isset($_SESSION[$this->key][$key])){

			if($_SESSION[$this->key][$key]['ttl'] === null || $_SESSION[$this->key][$key]['ttl'] > time()){
				return $_SESSION[$this->key][$key]['content'];
			}

			unset($_SESSION[$this->key][$key]);

		}

		return $default;
	}

	/**
	 * @param string   $key
	 * @param          $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function set(string $key, $value, int $ttl = null):bool{

		$_SESSION[$this->key][$key] = [
			'ttl' => $ttl ? time() + $ttl : null,
			'content' => $value,
		];

		return true;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete(string $key):bool{
		unset($_SESSION[$this->key][$key]);

		return true;
	}

	/**
	 * @return bool
	 */
	public function clear():bool{
		$_SESSION[$this->key] = [];

		return true;
	}

}
