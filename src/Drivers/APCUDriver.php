<?php
/**
 * Class APCUDriver
 *
 * @filesource   APCUDriver.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

class APCUDriver extends CacheDriverAbstract{

	/** @inheritdoc */
	public function get(string $key, $default = null){
		$value = apcu_fetch($key);

		return $value ?: $default;
	}

	/** @inheritdoc */
	public function set(string $key, $value, int $ttl = null):bool{
		return apcu_store($key, $value, $ttl);
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		return apcu_delete($key);
	}

	/** @inheritdoc */
	public function clear():bool{
		return apcu_clear_cache();
	}

}
