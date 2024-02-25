<?php
/**
 * Class MemoryCache
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use DateInterval;
use function time;

/**
 * Implements a cache in memory
 */
class MemoryCache extends CacheDriverAbstract{

	protected array $cache = [];

	/** @inheritdoc */
	public function get(string $key, mixed $default = null):mixed{
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
	public function set(string $key, mixed $value, int|DateInterval|null $ttl = null):bool{
		$ttl = $this->getTTL($ttl);

		if($ttl !== null){
			$ttl = (time() + $ttl);
		}

		$this->cache[$this->checkKey($key)] = ['ttl' => $ttl, 'content' => $value];

		return true;
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		unset($this->cache[$this->checkKey($key)]);

		return true;
	}

	/** @inheritdoc */
	public function clear():bool{
		$this->cache = [];

		return true;
	}

}
