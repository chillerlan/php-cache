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

	protected function setUp():void{
		$this->cache = new FileCache(new CacheOptions([
			'cacheFilestorage' => __DIR__.'/../.cache\\/', /* some additional trailing slashes... */
		]));

#		parent::setUp();
	}

	public function testFileCacheInvalidDirException(){
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('invalid cachedir');

		new FileCache(new CacheOptions(['cacheFilestorage' => 'foo']));
	}

	public function testFileCacheDirnotWritableException(){

		if(\PHP_OS_FAMILY === 'Windows'){
			$this->markTestSkipped('Windows');
			return;
		}

		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('cachedir is read-only. permissions?');

		$dir = __DIR__.'/writetest/';

		\mkdir($dir, 0000);

		new FileCache(new CacheOptions(['cacheFilestorage' => $dir]));
	}

}
