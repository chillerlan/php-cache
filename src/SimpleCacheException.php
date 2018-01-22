<?php
/**
 * Class SimpleCacheException
 *
 * @filesource   SimpleCacheException.php
 * @created      27.05.2017
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use Psr\SimpleCache\CacheException;

class SimpleCacheException extends \Exception implements CacheException{}
