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

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null){
		$value = apcu_fetch($key);

		return $value ? $value : $default;
	}

	/**
	 * @param string   $key
	 * @param          $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function set(string $key, $value, int $ttl = null):bool{
		return apcu_store($key, $value, $ttl);
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete(string $key):bool{
		return apcu_delete($key);
	}

	/**
	 * @return bool
	 */
	public function clear():bool{
		return apcu_clear_cache();
	}

	/**
	 * @param array    $values
	 * @param int|null $ttl
	 *
	 * @return bool
	public function setMultiple(array $values, int $ttl = null):bool{
		$result = apcu_store($values, null, $ttl);
		return empty($result);
	}
*/
}
