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

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use DateInterval;
use function apcu_clear_cache, apcu_delete, apcu_fetch, apcu_store, implode, is_array, is_bool, sprintf;

/**
 * Implements a cache via the APCu extension
 *
 * @see https://www.php.net/manual/en/book.apcu.php
 * @see https://github.com/krakjoe/apcu
 */
class APCUCache extends CacheDriverAbstract{

	/** @inheritdoc */
	public function get(string $key, mixed $default = null):mixed{
		$value = apcu_fetch($this->checkKey($key));

		if($value !== false){
			return $value;
		}

		return $default;
	}

	/**
	 * @inheritdoc
	 * @throws \Psr\SimpleCache\CacheException
	 */
	public function set(string $key, mixed $value, int|DateInterval|null $ttl = null):bool{
		$ret = apcu_store($this->checkKey($key), $value, ($this->getTTL($ttl) ?? 0));

		if(is_bool($ret)){
			return $ret;
		}

		if(is_array($ret)){
			throw new CacheException(sprintf('error keys: %s', implode(', ', $ret)));
		}

		throw new CacheException('unknown apcu_store() error');
	}

	/**
	 * @inheritdoc
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	public function delete(string $key):bool{
		$ret = apcu_delete($this->checkKey($key));

		if(is_bool($ret)){
			return $ret;
		}

		if(is_array($ret)){
			throw new CacheException(sprintf('error keys: %s', implode(', ', $ret)));
		}

		throw new CacheException('unknown apcu_delete() error');
	}

	/** @inheritdoc */
	public function clear():bool{
		return apcu_clear_cache();
	}

}
