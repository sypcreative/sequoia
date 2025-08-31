<?php //@phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Aruba HiSpeed Cache
 *
 * @category Wordpress-plugin
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @see      Null
 * @since    2.0.0
 * @package  ArubaHispeedCache
 */
namespace ArubaSPA\HiSpeedCache\Core;

use ArubaSPA\HiSpeedCache\Traits\Instance;
use ArubaSPA\HiSpeedCache\Traits\HasContainer;
use ArubaSPA\HiSpeedCache\Container\ContainerBuilder;

if ( ! \class_exists( __NAMESPACE__ . 'I18n' ) ) {
	/**
	 * Internationalization and localization definitions
	 *
	 * @package ArubaSPA\HiSpeedCache\Core
	 * @since 2.0.0
	 */
	final class I18n {
		use Instance;
		use HasContainer;

		/**
		 * Does the class support the given request or condition?
		 *
		 * If this returns true, boostrap() will be called. If false, the class will be skipped.
		 *
		 * @return boolean
		 */
		public function support() {
			return true;
		}

		/**
		 * The boostrap method.
		 *
		 * @return void
		 */
		public function boostrap() {
			\add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			//$this->load_plugin_textdomain();
		}

		/**
		 * Load the plugin text domain for translation
		 *
		 * @docs https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#loading-text-domain
		 *
		 * @since 2.0.0
		 * @return void
		 */
		public function load_plugin_textdomain() {
			\load_plugin_textdomain(
				$this->container->get_parameter( 'app.core.plugin_name' ),
				false,
				dirname( $this->container->get_parameter( 'app.constant.ARUBA_HISPEED_CACHE_BASENAME' ) ) . '/languages'
			);
		}
	}
}
