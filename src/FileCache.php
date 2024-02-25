<?php
/**
 * Class FileCache
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use DateInterval, FilesystemIterator, RecursiveDirectoryIterator, RecursiveIteratorIterator, stdClass;
use function dirname, file_get_contents, file_put_contents, hash, is_dir,
	is_file, mkdir, serialize, str_replace, substr, time, unlink, unserialize;
use const DIRECTORY_SEPARATOR;

class FileCache extends CacheDriverAbstract{

	/** @inheritdoc */
	public function get(string $key, mixed $default = null):mixed{
		$filename = $this->getFilepath($key);

		if(is_file($filename)){
			$content = file_get_contents($filename);

			if(!empty($content)){
				$data = unserialize($content);

				if($data->ttl === null || $data->ttl > time()){
					return $data->content;
				}

				unlink($filename);
			}

		}

		return $default;
	}

	/** @inheritdoc */
	public function set(string $key, mixed $value, int|DateInterval|null $ttl = null):bool{
		$ttl  = $this->getTTL($ttl);
		$file = $this->getFilepath($key);
		$dir  = dirname($file);

		if(!is_dir($dir)){
			mkdir($dir, 0755, true);
		}

		$data          = new stdClass;
		$data->ttl     = null;
		$data->content = $value;

		if($ttl !== null){
			$data->ttl = (time() + $ttl);
		}

		file_put_contents($file, serialize($data));

		if(is_file($file)){
			return true;
		}

		return false; // @codeCoverageIgnore
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		$filename = $this->getFilepath($key);

		if(is_file($filename)){
			return unlink($filename);
		}

		return false;
	}

	/** @inheritdoc */
	public function clear():bool{
		$dir      = $this->options->cacheFilestorage; // copy to avoid calling the magic getter in loop
		$return   = [];
		$iterator = new RecursiveDirectoryIterator(
			$dir,
			(FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS)
		);

		foreach(new RecursiveIteratorIterator($iterator) as $path){
			// skip files in the parent directory - cache files are only under /a/ab/[hash]
			if(!str_contains(str_replace($dir, '', $path), DIRECTORY_SEPARATOR)){
				continue;
			}

			$return[] = unlink($path);
		}

		return $this->checkReturn($return); // @codeCoverageIgnore
	}

	/**
	 *
	 */
	protected function getFilepath(mixed $key):string{
		$h      = hash('sha256', $this->checkKey($key));
		$subdir = '';

		for($i = 1; $i <= 3; $i++){ // @todo: subdir depth to options?
			$subdir .= substr($h, 0, $i).DIRECTORY_SEPARATOR;
		}

		return $this->options->cacheFilestorage.$subdir.$h;
	}

}
