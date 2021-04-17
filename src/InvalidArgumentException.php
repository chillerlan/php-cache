<?php
/**
 * Class InvalidArgumentException
 *
 * @created      25.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

class InvalidArgumentException extends CacheException implements \Psr\SimpleCache\InvalidArgumentException{}
