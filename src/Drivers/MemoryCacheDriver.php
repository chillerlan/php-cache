<?php
/**
 * Class MemoryCacheDriver
 *
 * @filesource   MemoryCacheDriver.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

class MemoryCacheDriver extends CacheDriverAbstract{

	/**
	 * @var array
	 */
	protected $cache = [];

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null){

		if(isset($this->cache[$key])){

			if($this->cache[$key]['ttl'] === null || $this->cache[$key]['ttl'] > time()){
				return $this->cache[$key]['content'];
			}

			unset($this->cache[$key]);
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

		$this->cache[$key] = [
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
		unset($this->cache[$key]);

		return true;
	}

	/**
	 * @return bool
	 */
	public function clear():bool{
		$this->cache = [];

		return true;
	}

}
