<?php
/**
 * Class FileCacheTest
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCacheTest;

use chillerlan\SimpleCache\FileCache;
use Psr\SimpleCache\CacheException;

use function file_exists, file_put_contents, mkdir;
use const PHP_OS_FAMILY;

class FileCacheTest extends SimpleCacheTestAbstract{

	protected const CACHEDIR = __DIR__.'/.cache/';
	protected const READONLY = __DIR__.'/.readonly/';

	protected function setUp():void{
		parent::setUp();

		if(!file_exists($this::CACHEDIR)){
			mkdir($this::CACHEDIR, 0777, true);
		}

		$this->options->cacheFilestorage = $this::CACHEDIR.'\\/'; // some additional trailing slashes...

		$this->cache = new FileCache($this->options);
	}

	public function testFileCacheInvalidDirException():void{
		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('invalid cachedir');

		$this->options->cacheFilestorage = 'foo';

		$this->cache = new FileCache($this->options);
	}

	public function testFileCacheDirnotWritableException():void{

		if(PHP_OS_FAMILY === 'Windows'){
			$this->markTestSkipped('Windows');
		}

		$this->expectException(CacheException::class);
		$this->expectExceptionMessage('cachedir is read-only. permissions?');

		$dir = $this::READONLY;

		mkdir($dir, 0444);

		$this->options->cacheFilestorage = $dir;

		$this->cache = new FileCache($this->options);
	}

	public function testClearIgnoresParentDirectory():void{
		$nodelete = $this::CACHEDIR.'some-file.txt';

		file_put_contents($nodelete, 'text');

		$this->cache->set('foo', 'bar');
		$this->cache->clear();

		$this::assertTrue(file_exists($nodelete));
	}

}
