<?php
/**
 * Class MemoryCache
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use function time;

class MemoryCache extends CacheDriverAbstract{

	protected array $cache = [];

	/** @inheritdoc */
	public function get($key, $default = null){
		$key = $this->checkKey($key);

		if(isset($this->cache[$key])){

			if($this->cache[$key]['ttl'] === null || $this->cache[$key]['ttl'] > time()){
				return $this->cache[$key]['content'];
			}

			unset($this->cache[$key]);
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		$ttl = $this->getTTL($ttl);

		$this->cache[$this->checkKey($key)] = [
			'ttl'     => $ttl ? time() + $ttl : null,
			'content' => $value,
		];

		return true;
	}

	/** @inheritdoc */
	public function delete($key):bool{
		unset($this->cache[$this->checkKey($key)]);

		return true;
	}

	/** @inheritdoc */
	public function clear():bool{
		$this->cache = [];

		return true;
	}

}
