<?php
/**
 * Class FileCacheDriver
 *
 * @filesource   FileCacheDriver.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache\Drivers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache\Drivers;

use chillerlan\SimpleCache\CacheException;
use chillerlan\Settings\SettingsContainerInterface;
use FilesystemIterator, RecursiveDirectoryIterator, RecursiveIteratorIterator, stdClass;

class FileCacheDriver extends CacheDriverAbstract{

	/**
	 * @var string
	 */
	protected $cachedir;

	/**
	 * FileCacheDriver constructor.
	 *
	 * @param \chillerlan\Settings\SettingsContainerInterface|null $options
	 *
	 * @throws \chillerlan\SimpleCache\CacheException
	 */
	public function __construct(SettingsContainerInterface $options = null){
		parent::__construct($options);

		$this->cachedir = rtrim($this->options->cacheFilestorage, '/\\').DIRECTORY_SEPARATOR;

		if(!is_dir($this->cachedir)){
			$msg = 'invalid cachedir "'.$this->cachedir.'"';

			$this->logger->error($msg);
			throw new CacheException($msg);
		}

		if(!is_writable($this->cachedir)){
			$msg = 'cachedir is read-only. permissions?';

			$this->logger->error($msg);
			throw new CacheException($msg);
		}

	}

	/** @inheritdoc */
	public function get(string $key, $default = null){
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
	public function set(string $key, $value, int $ttl = null):bool{
		$file = $this->getFilepath($key);
		$dir  = dirname($file);

		if(!is_dir($dir)){
			mkdir($dir, 0755, true);
		}

		$data          = new stdClass;
		$data->ttl     = null;
		$data->content = $value;

		if($ttl !== null){
			$data->ttl = time() + $ttl;
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
		$iterator = new RecursiveDirectoryIterator($this->cachedir, FilesystemIterator::CURRENT_AS_PATHNAME|FilesystemIterator::SKIP_DOTS);
		$return   = [];

		foreach(new RecursiveIteratorIterator($iterator) as $path){

			// skip files the parent directory - cache files are only under /a/ab/[hash]
			if(strpos(str_replace($this->cachedir, '', $path), DIRECTORY_SEPARATOR) === false){
				continue;
			}

			$return[] = unlink($path);
		}

		return $this->checkReturn($return); // @codeCoverageIgnore
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	protected function getFilepath(string $key):string{
		$h = hash('sha256', $key);

		return $this->cachedir.$h[0].DIRECTORY_SEPARATOR.$h[0].$h[1].DIRECTORY_SEPARATOR.$h;
	}

}
