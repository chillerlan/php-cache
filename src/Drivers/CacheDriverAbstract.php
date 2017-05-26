<?php
/**
 * Class CacheDriverAbstract
 *
 * @filesource   CacheDriverAbstract.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

abstract class CacheDriverAbstract implements CacheDriverInterface{

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key):bool{
		return (bool)$this->get($key);
	}

	/**
	 * @param array $keys
	 * @param null  $default
	 *
	 * @return array
	 */
	public function getMultiple(array $keys, $default = null):array{
		$data = [];

		foreach($keys as $key){
			$data[$key] = $this->get($key, $default);
		}

		return $data;
	}

	/**
	 * @param array    $values
	 * @param int|null $ttl
	 *
	 * @return bool
	 */
	public function setMultiple(array $values, int $ttl = null):bool{
		$return = [];

		if(!empty($values)){

			foreach($values as $key => $value){
				$return[] = $this->set($key, $value, $ttl);
			}

		}

		return $this->checkReturn($return);
	}

	/**
	 * @param array $keys
	 *
	 * @return bool
	 */
	public function deleteMultiple(array $keys):bool{
		$return = [];

		if(!empty($keys)){

			foreach($keys as $key){
				$return[] = $this->delete($key);
			}

		}

		return $this->checkReturn($return);
	}

	/**
	 * @param bool[] $booleans
	 *
	 * @return bool
	 */
	protected function checkReturn(array $booleans):bool{

		foreach($booleans as $bool){
			if(!$bool){
				return false;
			}
		}

		return true;
	}

}
