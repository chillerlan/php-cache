<?php
/**
 * Interface CacheDriverInterface
 *
 * @filesource   CacheDriverInterface.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

interface CacheDriverInterface{

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get(string $key, $default = null);

	/**
	 * @param string   $key
	 * @param          $value
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function set(string $key, $value, int $ttl = null):bool;

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete(string $key):bool;

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key):bool;

	/**
	 * @return bool
	 */
	public function clear():bool;

	/**
	 * @param array $keys
	 * @param null  $default
	 *
	 * @return array
	 */
	public function getMultiple(array $keys, $default = null):array;

	/**
	 * @param array    $values
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function setMultiple(array $values, int $ttl = null):bool;

	/**
	 * @param array $keys
	 *
	 * @return bool
	 */
	public function deleteMultiple(array $keys):bool;

}
