<?php
/**
 * Class InvalidArgumentException
 *
 * @filesource   InvalidArgumentException.php
 * @created      25.05.2017
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

class InvalidArgumentException extends CacheException implements \Psr\SimpleCache\InvalidArgumentException{}
