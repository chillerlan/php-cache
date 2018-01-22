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

use chillerlan\SimpleCache\SimpleCacheException;
use stdClass;

class FileCacheDriver extends CacheDriverAbstract{

	/**
	 * @var string
	 */
	protected $cachedir;

	/**
	 * FileCacheDriver constructor.
	 *
	 * @param string $cachedir
	 *
	 * @throws \chillerlan\SimpleCache\SimpleCacheException
	 */
	public function __construct(string $cachedir){
		$this->cachedir = $cachedir;

		if(!is_dir($cachedir)){
			throw new SimpleCacheException('invalid cachedir "'.$cachedir.'"');
		}

		if(!is_writable($cachedir)){
			throw new SimpleCacheException('cachedir is read-only. permissions?'); // @codeCoverageIgnore
		}

	}

	/** @inheritdoc */
	public function get(string $key, $default = null){
		$filename = $this->filename($key);

		if(is_file($filename)){
			$content = file_get_contents($filename);

			if(!empty($content)){
				$data = unserialize($content);

				if(is_null($data->ttl) || $data->ttl > time()){
					return $data->content;
				}

				unlink($filename);
			}

		}

		return $default;
	}

	/** @inheritdoc */
	public function set(string $key, $value, int $ttl = null):bool{
		$filename      = $this->filename($key);
		$data          = new stdClass;
		$data->ttl     = null;
		$data->content = $value;

		if($ttl !== null){
			$data->ttl = time() + $ttl;
		}

		file_put_contents($filename, serialize($data));

		if(is_file($filename)){
			return true;
		}

		return false; // @codeCoverageIgnore
	}

	/** @inheritdoc */
	public function delete(string $key):bool{
		$filename = $this->filename($key);

		if(is_file($filename)){
			return unlink($filename);
		}

		return false;
	}

	/** @inheritdoc */
	public function clear():bool{
		$dir = scandir($this->cachedir);

		if(is_array($dir) && !empty($dir)){
			$return = [];

			foreach($dir as $file){
				$path = $this->cachedir.DIRECTORY_SEPARATOR.$file;

				if(is_file($path) && strlen($file) === 64){
					$return[] = unlink($path);
				}

			}

			return $this->checkReturn($return);
		}

		return true; // @codeCoverageIgnore
	}

	/**
	 * @param string $file
	 *
	 * @return string
	 */
	protected function filename(string $file):string {
		return $this->cachedir.DIRECTORY_SEPARATOR.hash('sha256', $file);
	}

}
