<?php
/**
 * Class APCUCache
 *
 * @filesource   APCUCache.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

class APCUCache extends CacheDriverAbstract{

	/** @inheritdoc */
	public function get($key, $default = null){
		$this->checkKey($key);

		$value = apcu_fetch($key);

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		$this->checkKey($key);

		return (bool)apcu_store($key, $value, $this->getTTL($ttl));
	}

	/** @inheritdoc */
	public function delete($key):bool{
		$this->checkKey($key);

		return (bool)apcu_delete($key);
	}

	/** @inheritdoc */
	public function clear():bool{
		return apcu_clear_cache();
	}

}
