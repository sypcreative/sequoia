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
 * Adds a feature set to allow instantiation of plugin and theme objects.
 */
trait Instance {

	/**
	 * Instance of this class.
	 *
	 * @since    2.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @see    https://wordpress.stackexchange.com/questions/70055/best-way-to-initiate-a-class-in-a-wp-plugin
	 * @since  2.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
