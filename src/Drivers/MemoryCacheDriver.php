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

	/** @inheritdoc */
	public function get(string $key, $default = null){

		if(isset($this->cache[$key])){

			if($this->cache[$key]['ttl'] === null || $this->cache[$key]['ttl'] > time()){
				return $this->cache[$key]['content'];
			}

			unset($this->cache[$key]);
		}

		return $default;
	}

	/** @inheritdoc */
	public function set(string $key, $value, int $ttl = null):bool{

		$this->cache[$key] = [
			'ttl' => $ttl ? time() + $ttl : null,
			'content' => $value,
		];

		return true;
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		unset($this->cache[$key]);

		return true;
	}

	/** @inheritdoc */
	public function clear():bool{
		$this->cache = [];

		return true;
	}

}
