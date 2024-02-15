<?php
/**
 * Trait CacheOptionsTrait
 *
 * @created      08.08.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use function is_dir, is_writable, realpath, rtrim, sprintf;
use const DIRECTORY_SEPARATOR;

trait CacheOptionsTrait{

	/**
	 * the file storage root directory
	 */
	protected string $cacheFilestorage = '';

	/**
	 * the key name for the session cache
	 */
	protected string $cacheSessionkey  = '_session_cache';

	/**
	 * @throws \Psr\SimpleCache\CacheException
	 */
	protected function set_cacheFilestorage(string $dir):void{
		$dir = rtrim($dir, '\\/');

		if(!is_dir($dir)){
			throw new CacheException(sprintf('invalid cachedir "%s"', $dir));
		}

		if(!is_writable($dir)){
			throw new CacheException('cachedir is read-only. permissions?');
		}

		$this->cacheFilestorage = realpath($dir).DIRECTORY_SEPARATOR;
	}

}
