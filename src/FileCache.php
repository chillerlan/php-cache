<?php
/**
 * Class FileCache
 *
 * @filesource   FileCache.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerInterface;
use FilesystemIterator, RecursiveDirectoryIterator, RecursiveIteratorIterator, stdClass;

class FileCache extends CacheDriverAbstract{

	/**
	 * @var string
	 */
	protected $cachedir;

	/**
	 * FileCache constructor.
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
	public function get($key, $default = null){
		$filename = $this->getFilepath($this->checkKey($key));

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
	public function set($key, $value, $ttl = null):bool{
		$ttl = $this->getTTL($ttl);

		$file = $this->getFilepath($this->checkKey($key));
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
	public function delete($key):bool{
		$filename = $this->getFilepath($this->checkKey($key));

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

			// skip files in the parent directory - cache files are only under /a/ab/[hash]
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
