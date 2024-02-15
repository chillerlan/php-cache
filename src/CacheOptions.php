<?php
/**
 * Class CacheOptions
 *
 * @created      23.01.2018
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

declare(strict_types=1);

namespace chillerlan\SimpleCache;

use chillerlan\Settings\SettingsContainerAbstract;

/**
 *
 */
class CacheOptions extends SettingsContainerAbstract{
	use CacheOptionsTrait;
}
