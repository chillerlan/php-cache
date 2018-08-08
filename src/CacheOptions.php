<?php
/**
 * Class CacheOptions
 *
 * @filesource   CacheOptions.php
 * @created      23.01.2018
 * @package      chillerlan\SimpleCache
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use chillerlan\Traits\ImmutableSettingsAbstract;

/**
 * @property string $filestorage
 * @property string $cachekey
 */
class CacheOptions extends ImmutableSettingsAbstract{
	use CacheOptionsTrait;
}
