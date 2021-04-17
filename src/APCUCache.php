<?php
/**
 * Class APCUCache
 *
 * @created      27.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpComposerExtensionStubsInspection
 */

namespace chillerlan\SimpleCache;

use function apcu_clear_cache, apcu_delete, apcu_fetch, apcu_store;

class APCUCache extends CacheDriverAbstract{

	/** @inheritdoc */
	public function get($key, $default = null){
		$value = apcu_fetch($this->checkKey($key));

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/** @inheritdoc */
	public function set($key, $value, $ttl = null):bool{
		return (bool)apcu_store($this->checkKey($key), $value, $this->getTTL($ttl) ?? 0);
	}

	/** @inheritdoc */
	public function delete($key):bool{
		return (bool)apcu_delete($this->checkKey($key));
	}

	/** @inheritdoc */
	public function clear():bool{
		return apcu_clear_cache();
	}

}
