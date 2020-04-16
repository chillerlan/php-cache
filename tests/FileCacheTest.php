<?php
/**
 * Class FileCacheTest
 *
 * @filesource   FileCacheTest.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCacheTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\{CacheOptions, FileCache};
use Psr\SimpleCache\CacheException;

class FileCacheTest extends SimpleCacheTestAbstract{

	protected const CACHEDIR = __DIR__.'/../.build/cache/';
	protected const READONLY = __DIR__.'/../.build/readonly/';

	protected function setUp():void{

		if(!\file_exists($this::CACHEDIR)){
			\mkdir($this::CACHEDIR, 0777, true);
		}

		$this->cache = new FileCache(new CacheOptions([
			'cacheFilestorage' => $this::CACHEDIR.'\\/', /* some additional trailing slashes... */
		]));
	}

	public function testFileCacheInvalidDirException(){
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('invalid cachedir');

		$c = new FileCache(new CacheOptions(['cacheFilestorage' => 'foo']));
	}

	public function testFileCacheDirnotWritableException(){

		if(\PHP_OS_FAMILY === 'Windows'){
			$this->markTestSkipped('Windows');
			return;
		}

		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('cachedir is read-only. permissions?');

		$dir = $this::READONLY;

		\mkdir($dir, 0000);

		$c = new FileCache(new CacheOptions(['cacheFilestorage' => $dir]));
	}

	public function testClearIgnoresParentDirectory(){
		$nodelete = $this::CACHEDIR.'some-file.txt';

		\file_put_contents($this::CACHEDIR.'some-file.txt', 'text');

		$this->cache->set('foo', 'bar');
		$this->cache->clear();

		$this->assertTrue(\file_exists($nodelete));
	}

}
