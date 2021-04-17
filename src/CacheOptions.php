<?php
/**
 * Class CacheOptions
 *
 * @created      23.01.2018
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerAbstract;

/**
 * @property string $cacheFilestorage
 * @property string $cacheSessionkey
 */
class CacheOptions extends SettingsContainerAbstract{
	use CacheOptionsTrait;
}
