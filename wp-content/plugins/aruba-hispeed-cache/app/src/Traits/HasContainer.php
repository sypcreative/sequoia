<?php //@phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Aruba HiSpeed Cache Traits
 *
 * @category Wordpress-plugin
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @see      Null
 * @since    2.0.0
 * @package  ArubaHispeedCache
 */


namespace ArubaSPA\HiSpeedCache\Traits;

use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

/**
 * Adds a feature set to allow Container of plugin and theme objects.
 */
trait HasContainer {

	/**
	 * Instance of this class.
	 *
	 * @since    2.0.0
	 *
	 * @var      object
	 */
	protected $container = null;

	/**
	 * Get the container
	 *
	 * @return \ArubaSPA\HiSpeedCache\Container\ContainerBuilder
	 * @SuppressWarnings(PHPMD.StaticAccess)
	 */
	public function get_container() {
		$this->container = ContainerBuilder::get_instance();
		return $this->container;
	}
}
